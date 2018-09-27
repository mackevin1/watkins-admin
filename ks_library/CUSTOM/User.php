<?php

require_once 'User_Base.php';

class CUSTOM_User extends CUSTOM_User_Base {

	const ROLE_ADMIN = 'ADMIN';
	const ROLE_USER = 'USER';
	const ROLE_MANAGER = 'MANAGER';
	
	//deprecated.. all roles are now retrieved from ks_acl_roles
	//public static $ROLES = array ('ADMIN','USER');
	
	protected $authStatus; // if 1, means user is authenticated. 0 otherwise (wrong password, userid disabled or session expired).
	protected $authNotfound; // if 1, means User ID is not found.
	protected $authWrongpassword;
	protected $authDisabled;
	
	/**
	 * This method authenticates user by checking password and set the sessions.
	 */
	public function authenticate() {
		try {
			global $ks_log;
			
			if (! $this->id) {
				echo "Fatal Error: Userid is not set in " . __METHOD__;
				exit ();
			}
			if (! $this->password) {
				echo "Fatal Error: Password is not set in " . __METHOD__;
				exit ();
			}
			
			// checks if User ID is found..
			if (! $this->exists ()) {
				$this->authNotfound = 1;
				return; // exits this method..
			}
			
			global $ks_db;
			$sql = "SELECT * FROM $this->sqlTable 
					WHERE usr_id = ? AND 
					usr_password = MD5( CONCAT('$this->password',usr_salt) ) ";
			
			$arrBindings = array ();
			$arrBindings [] = $this->id;
			
			$stmt = $ks_db->query ( $sql, $arrBindings );
			$rowCount = $stmt->rowCount ();
			
			if ($rowCount == 0) {
				$this->authWrongpassword = 1;
				return;
			}
			
			$this->select ();
			
			if ($this->getEnabled () == 0) {
				$this->authDisabled = 1;
				return;
			}
			
			session_start ();
			$ks_session_group = KSCONFIG_DB_NAME;
			
			// we group session by a unique identifier, hopefully database instance name (KSCONFIG_DB_NAME)
			// this is to avoid jumping to another system within the same server
			$_SESSION [$ks_session_group] ['USR_ID'] = $this->id;
			$_SESSION [$ks_session_group] ['USR_NAME'] = $this->name;
			$_SESSION [$ks_session_group] ['USR_EMAIL'] = $this->email;
			$_SESSION [$ks_session_group] ['USR_ROLE'] = $this->role;
		} catch ( Exception $e ) {
			$ks_log->info ( 'Fatal Error: ' . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage () );
			echo "Fatal Error: " . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage ();
		}
	}
	
	/**
	 * This method checks if user is authenticated.
	 *
	 * @return true if authenticated. false otherwise.
	 */
	public static function checkAuthentication() {
		try {
			
			global $ks_log;

			if (session_id () == '') {
				session_start ();
			}
			
			$id = '';
			
			$ks_session_group = KSCONFIG_DB_NAME;
			if (isset ( $_SESSION [$ks_session_group] )) {
				$id = $_SESSION [$ks_session_group] ['USR_ID'];
			}
			
			if (strlen ( $id ) > 0) {
				return true;
			} else {
				return false;
			}
		} catch ( Exception $e ) {
			$ks_log->info ( 'Fatal Error: ' . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage () );
			echo "Fatal Error: " . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage ();
		}
	}
	
	/**
	 * This method returns user details of login user, from SESSION variables
	 *
	 * @return true if authenticated. false otherwise.
	 */
	public static function getSessionData() {
		try {
			global $ks_log;
			
			if (session_id () == '') {
				session_start ();
			}
			$ks_session_group = KSCONFIG_DB_NAME;
			
			if (isset ( $_SESSION [$ks_session_group] )) {
				return $_SESSION [$ks_session_group];
			} else {
				return false;
			}
			
		} catch ( Exception $e ) {
			$ks_log->info ( 'Fatal Error: ' . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage () );
			echo "Fatal Error: " . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage ();
		}
	}
	
	/**
	 * This method returns user details of login user, from SESSION variables
	 *
	 * @return true if authenticated. false otherwise.
	 */
	public static function isAdmin() {
		try {
			global $ks_log;

			if (session_id () == '') {
				session_start ();
			}
			
			$ks_session_group = KSCONFIG_DB_NAME;
			
			if (isset ( $_SESSION [$ks_session_group] )) {
				if ($_SESSION [$ks_session_group] ['USR_ROLE'] == self::ROLE_ADMIN) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}

		} catch ( Exception $e ) {
			$ks_log->info ( 'Fatal Error: ' . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage () );
			echo "Fatal Error: " . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage ();
		}
	}
	
	/**
	 * This method checks if email is taken by another user
	 * 
	 * @param $email: email
	 *        	to check
	 * @param $userid: if
	 *        	specified, $email is owned by this user (usually when modify)
	 * @return false/$userid, $userid=userid who uses the email, false=not used by anyone (or if $userid specified, used by this user)
	 */
	public static function emailTaken($email, $userid = '') {
		try {
			global $ks_log;
			global $ks_db;
			
			if (! $email) {
				echo "Fatal Error: Email is not set in " . __METHOD__;
				exit ();
			}
			
			$arrBindings = array ();
			
			if (isset ( $userid )) {
				$sql = "SELECT usr_id FROM t_user
						WHERE usr_email = ? AND usr_id != ?";
				$arrBindings [] = $email;
				$arrBindings [] = $userid;
			} else {
				$sql = "SELECT usr_id FROM t_user
						WHERE usr_email = ? ";
				$arrBindings [] = $email;
			}
			
			$usr_id = $ks_db->fetchOne ( $sql, $arrBindings );
			
			if ($usr_id) {
				return $usr_id;
			} else {
				return false;
			}
		} catch ( Exception $e ) {
			$ks_log->info ( 'Fatal Error: ' . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage () );
			echo "Fatal Error: " . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage ();
		}
	}
	
	/**
	 *
	 * @return the $authStatus
	 */
	public function getAuthStatus() {
		return $this->authStatus;
	}
	
	/**
	 *
	 * @return the $authNotfound
	 */
	public function getAuthNotfound() {
		return $this->authNotfound;
	}
	
	/**
	 *
	 * @return the $authWrongpassword
	 */
	public function getAuthWrongpassword() {
		return $this->authWrongpassword;
	}
	
	/**
	 *
	 * @return the $authDisabled
	 */
	public function getAuthDisabled() {
		return $this->authDisabled;
	}
}
