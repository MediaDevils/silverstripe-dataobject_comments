<?php
class DataObjectComment extends DataObject {
	public static $singular_name = "Comment";
	public static $plural_name = "Comments";

	public static $db = array(
		"Type" => "Varchar(20)",
		"TargetID" => "Int",
		"TargetType" => "Text"
	);
	
	public static $defaults = array(
		"Type" => "Comment"
	);
	
	public function scaffoldFormFields($_params = null) {
		$fields = parent::scaffoldFormFields($_params);
		
		$fields->removeByName("Type");
		
		$IDField = $fields->fieldByName("TargetID");
		$newIDField = new HiddenField("TargetID");
		$newIDField->setValue($IDField->dataValue());
		$fields->replaceField("TargetID", $newIDField);
		
		$TypeField = $fields->fieldByName("TargetType");
		$newTypeField = new HiddenField("TargetType");
		$newTypeField->setValue($TypeField->dataValue());
		$fields->replaceField("TargetType", $newTypeField);
		
		return $fields;
	}
	
	public function getRequiredFields() {
		$required = new RequiredFields();
		$this->extend("updateRequiredFields", $required);
		
		return $required;
	}
	
	public function valid() {
		if($this->owner->TargetID && $this->owner->TargetType) {
			if($this->Target())
				return true;
		}
		return false;
	}
	
	public function Target() {
		return ($this->TargetType)::get()->byID($this->TargetID);
	}
	
	public function Link($action = null, $includeHash = true) {
		$target = $this->Target();
		if($target)
			return $target->Link($action).($includeHash?"#comment-{$this->ID}":"");
		else return false;
	}
	
	public function FormRemoveComment() {
		return singleton("DataObjectComments")->FormRemoveComment(null, $this);
	}
}
