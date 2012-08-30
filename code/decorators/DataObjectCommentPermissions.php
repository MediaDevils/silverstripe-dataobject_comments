<?php
class DataObjectCommentPermissions extends DataExtension implements PermissionProvider {
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
		if($ownerid && Permission::check("DATAOBJECTCOMMENT_REMOVE")) {
			if($ownerid == Member::currentUserID()) return true;
		}
		$target = $this->owner->Target();
		if($target && Permission::check("DATAOBJECTCOMMENT_REMOVE")) {
			if($target->hasMethod("UserCanRemove") && $target->UserCanRemove()) return true; // User can remove comments on their comments, assets, etc
			if($target->is_a("Member") && Member::currentUserID() && $target->IsCurrentUser()) return true;
		}
		return false;
	}
}
