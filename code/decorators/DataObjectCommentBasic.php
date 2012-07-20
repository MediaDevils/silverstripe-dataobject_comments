<?php
class DataObjectCommentBasic extends DataObjectDecorator {
	public function extraStatics() {
		return array(
			"db" => array(
				"Content" => "Text"
			),
			"has_one" => array(
				"Owner" => "Member"
			)
		);
	}
	
	public function updateFrontEndFields(&$fields) {
		$fields->removeByName("OwnerID");
		return $fields;
	}
	
	public function handleComment(Form &$form) {
		$this->owner->OwnerID = Member::currentUserID();
	}
}
