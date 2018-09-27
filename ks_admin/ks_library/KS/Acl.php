<?php

require_once ('Zend/Acl.php');

class KS_Acl extends Zend_Acl {
	
	const SQL_TABLE_RESOURCE = 'ks_acl_resource';
	const SQL_TABLE_ROLE = 'ks_acl_role';
	const SQL_TABLE_ACCESS = 'ks_acl_access';
	const SQL_TABLE_INHERITANCE = 'ks_acl_inheritance';
	
	/** 
	 * This class extends a standard Zend_ACL for use with a database. 
	 * Written by Michael MistaGee Ziegler 
	 * License: LGPL v2 or above 
	 * The constructor expects a Zend_DB object as parameter. 
	 * 
	 * 
	 * Database structure: 
	 * 
	 * +------------------+       +----------------+       +-------------+      +--------------+ 
	 * |    Resources     |       |    Access      |       |   Roles     |      |  Inheritance | 
	 * +------------------+       +----------------+       +-------------+      +--------------+ 
	 * | *id              |<--.   | *role_id       |------>| *id         |<-----| *child_id    | 
	 * | parent_id        |---'`--| *resource_id  N|       +-------------+   `--| *parent_id   | 
	 * | *privilege      N|<------| *privilege    N|                            | order        | 
	 * +------------------+       | allow          |                            +--------------+ 
	 *                            +----------------+ 
	 * 
	 * 
	 *   *field = PRIMARY KEY( field ) 
	 *   -----> = foreign key constraint 
	 * 
	 *   The actual table names should be: acl_resources, acl_access, acl_roles, acl_inheritance. 
	 * 
	 * access.allow is a boolean field, that specifies whether the respective rule is an allow rule or a deny rule (important for inherited access). 
	 * 
	 * The inheritance table stores which Role is to inherit rights from which parent rules. There can 
	 * be multiple parent rules. If a rule inherits rights from more than one parent, the first rule applicable 
	 * will be used to determine whether to allow or deny the access rights in question. 
	 * The order field stores in which order the parents are to be introduced to Zend_ACL, effectively setting 
	 * the order the parent rights are evaluated in. 
	 * Using a relational database for this is strongly advised, as it guarantees data integrity. 
	 * 
	 * If you intend to give each resource a specific name or collect other data about it, you should create 
	 * an extra table storing this data and put a foreign key referencing this into the resources table. Same 
	 * goes for the privileges. 
	 * 
	 */
	
	public function __construct() {
		
		global $ks_db;
		
		/// First: Create all the resources we have. 
		$resources = $ks_db->fetchAll ( $ks_db->select ()->distinct ()->from ( self::SQL_TABLE_RESOURCE, array ('res_id', 'res_parentid' ) ) );
		
		$resCount = count ( $resources );
		$addCount = 0;
		
		$allResources = array ();
		foreach ( $resources as $theRes ) {
			$allResources [] = $theRes ['res_id'];
		}
		
		foreach ( $resources as $theRes ) {
			if ($theRes ['res_parentid'] !== null && ! in_array ( $theRes ['res_parentid'], $allResources )) {
				require_once 'Zend/Acl/Exception.php';
				throw new Zend_Acl_Exception ( "Resource id '(" . $theRes ['res_parentid'] . ")' does not exist" );
			}
		}
		
		while ( $resCount > $addCount ) {
			foreach ( $resources as $theRes ) {
				// Check if parent resource (if any) exists 
				// Only add if this resource hasn't yet been added and its parent is known, if any 
				if (! $this->has ( $theRes ['res_id'] ) && ($theRes ['res_parentid'] === null || $this->has ( $theRes ['res_parentid'] ))) {
					$this->add ( new Zend_Acl_Resource ( $theRes ['res_id'] ), $theRes ['res_parentid'] );
					$addCount ++;
				}
			}
		}
		
		/// Now create all roles 
		$roles = $ks_db->fetchAll ( $ks_db->select ()->from ( array ('r' => self::SQL_TABLE_ROLE ), array ('r.role_id', 'i.inh_parentid' ) )->joinLeft ( array ('i' => self::SQL_TABLE_INHERITANCE ), 'r.role_id=i.inh_childid' )->order ( array ('inh_childid', 'inh_order' ) ) );
		
		// Create an array that stores all roles and their parents 
		$dbElements = array ();
		foreach ( $roles as $theRole ) {
			if (! isset ( $dbElements [$theRole ['role_id']] ))
				$dbElements [$theRole ['role_id']] = array ();
			if ($theRole ['inh_parentid'] !== null)
				$dbElements [$theRole ['role_id']] [] = $theRole ['inh_parentid'];
		}
		
		// Now add to the ACL 
		$dbElemCount = count ( $dbElements );
		$aclElemCount = 0;
		
		// while there are still elements left to be added 
		while ( $dbElemCount > $aclElemCount ) {
			// Check every element in the db 
			foreach ( $dbElements as $theDbElem => $theDbElemParents ) {
				// Check if a parent is invalid to prevent an infinite loop 
				// if the relational DBase works, this shouldn't happen 
				foreach ( $theDbElemParents as $theParent ) {
					if (! array_key_exists ( $theParent, $dbElements )) {
						require_once 'Zend/Acl/Exception.php';
						throw new Zend_Acl_Exception ( "Role id '$theParent' does not exist" );
					}
				}
				if (! $this->hasRole ( $theDbElem ) && (empty ( $theDbElemParents ) || $this->hasAllRolesOf ( $theDbElemParents ))) {
					// if it has not yet been added to the ACL 
					// and no parents exist or 
					// we know them all 
					// we can add to ACL 
					$this->addRole ( new Zend_Acl_Role ( $theDbElem ), $theDbElemParents );
					$aclElemCount ++;
				}
			}
		}
		
		/// Now create all access rules 
		$access = $ks_db->fetchAll ( $ks_db->select ()->from ( self::SQL_TABLE_ACCESS, array ('acc_roleid', 'acc_resid', 'acc_privilegeid', 'acc_allow' ) ) );
		
		foreach ( $access as $theRule ) {
			if ($theRule ['acc_allow'] == true)
				$this->allow ( $theRule ['acc_roleid'], $theRule ['acc_resid'], $theRule ['acc_privilegeid'] );
			else
				$this->deny ( $theRule ['acc_roleid'], $theRule ['acc_resid'], $theRule ['acc_privilegeid'] );
		}
	}
	
	public function hasAllRolesOf(array &$searchRoles) {
		foreach ( $searchRoles as $theRole )
			if (! $this->hasRole ( $theRole ))
				return false;
		return true;
	}
	
	/*
	 * This method extends Zend_Acl::isAllowed, to support multiple roles
	 * If parameter $role is an array, loop it
	 * Otherwise, simply call Zend_Acl::isAllowed
	 */
	public function isAllowed($roles = null, $resource = null, $privilege = null) {
		
		/**
		 * If roles is an array
		 */
		if (is_array ( $roles )) {
			
			foreach ( $roles as $curRole ) {
				$allowed = parent::isAllowed ( $curRole, $resource, $privilege );
				if (true == $allowed) {
					break;
				}
			}
		
		/**
		 * If a string separated by ; delimiters
		 */
		} elseif (preg_match ( '/;/', $roles )) {
			$arrRoles = explode ( ";", $roles );
			foreach ( $arrRoles as $curRole ) {
				if (trim ( $curRole )) {
					$allowed = parent::isAllowed ( $curRole, $resource, $privilege );
				
				}
			}
		
		/**
		 * If a single string (for compatibility) 
		 */
		} else {
			if (trim ( $roles )) {
				$allowed = parent::isAllowed ( $roles, $resource, $privilege );
			}
		}
		
		return $allowed;
	}

}

?>