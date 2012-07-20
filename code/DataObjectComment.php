<?php
class DataObjectComment extends DataObject {
	public static $db = array(
		"TargetID" => "Int",
		"TargetType" => "Text"
	);
	
	public function scaffoldFormFields($_params = null) {
		$fields = parent::scaffoldFormFields($_params);
		
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
	
	public function valid() {
		if($this->owner->TargetID && $this->owner->TargetType) {
			if($this->Target())
				return true;
		}
		return false;
	}
	
	public function Target() {
		return DataObject::get_by_id($this->TargetType, $this->TargetID);
	}
}