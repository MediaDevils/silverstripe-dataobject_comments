<?php
class DataObjectCommentPermissions extends DataObjectDecorator implements PermissionProvider {
	public function providePermissions() {
		return array(
			"DATAOBJECTCOMMENT_ADD" => "Add DataObject comments",
			"DATAOBJECTCOMMENT_REMOVE" => "Remove own DataObject comments",
			"DATAOBJECTCOMMENT_REMOVE_ANY" => "Remove *any* DataObject comments"
		);
	}
	
	public function UserCanRemove() {
		if(Permission::check("DATAOBJECTCOMMENT_REMOVE_ANY")) return true;
		$ownerid = $this->owner->OwnerID;
		if($owner && Permission::check("DATAOBJECTCOMMENT_REMOVE")) {
			if($ownerid === Member::currentUserID()) return true;
		}
		return false;
	}
}
