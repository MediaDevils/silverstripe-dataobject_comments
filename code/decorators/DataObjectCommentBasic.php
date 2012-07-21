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
		
		$ContentField = $fields->fieldByName("Content");
		$newContentField = new TextareaField("Content");
		$newContentField->setValue($ContentField->dataValue());
		$fields->replaceField("Content", $newContentField);
		
		return $fields;
	}
	
	public function handleAdd(Form &$form) {
		$this->owner->OwnerID = Member::currentUserID();
	}
	
	public function ConvertedContent($limit = null) {
		$content = $this->owner->dbObject("Content");
		if($limit)
			$content = $content->LimitCharacters($limit);
		else
			$content = $content->forTemplate();
		$this->owner->extend("ConvertContent", $content);
		return $content;
	}
	
	public function ConvertContent(&$content) {
		$content = preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@i', '<a href="$1" target="_blank">$1</a>', $content);
	}
}
