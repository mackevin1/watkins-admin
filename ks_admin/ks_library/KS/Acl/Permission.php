<?php

class KS_Acl_Permission {
	
	protected $id;
	protected $roleId;
	protected $resourceId;
	protected $allowDeny;
	
	/**
	 * This function returns the permission based on supplied roleid and resourceid
	 * @param int $roleId
	 * @param int $resourceId
	 * @return (0 = deny, 1 = allow) $allowdeny
	 */
	public function getPermissionByRoleResource($roleId, $resourceId) {
		global $ks_db;
		
		/**
		 * Get allowDeny from ks_acl_permission 
		 */
		
		$select = $ks_db->select()
			->from('ks_acl_permission')
			->where("per_roleid = '$roleId' AND per_resourceid = '$resourceId'");
			
		$result = $ks_db->fetchAll($select);

		$this->allowDeny = $result[0]['per_allowdeny'];
	}

}

?>