<?php
class DataObjectCommentBasic extends DataObjectDecorator {
	public static $options = array(
		"linkShownLength" => 10
	);

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
	
	public function TargetIsOwner() {
		return $this->owner->TargetType == "Member" && $this->owner->TargetID = $this->owner->OwnerID;
	}
	
	public function TargetIsNotOwner() {
		return !$this->TargetIsOwner();
	}
	
	public function ConvertedContent($limit = null, $rich = true) {
		$content = $this->owner->dbObject("Content");
		if($limit)
			$content = $content->LimitCharacters($limit?:null);
		else
			$content = $content->forTemplate();
		if($rich)
			$this->owner->extend("ConvertRich", $content);
		$this->owner->extend("ConvertContent", $content);
		return $content;
	}
	
	public function ConvertContent(&$content) {
		$matched = preg_match_all('@((?<![\w"])https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@i', $content, $matches);
		if($matched) {
			$matches = array_unique($matches);
			foreach($matches[0] as $url) {
				$parsedURL = parse_url($url);
				$host = $parsedURL["host"];
				$remainder = $parsedURL["path"].(isset($parsedURL["query"])?"?{$parsedURL["query"]}":"");
				if(isset(self::$options["linkShownLength"]) && self::$options["linkShownLength"])
					$remainder = (strlen($remainder) > self::$options["linkShownLength"])?
						substr($remainder, 0, self::$options["linkShownLength"])."..."
						:$remainder;
				$content = str_replace($url, " <a href=\"{$url}\" target=\"_blank\">{$host}{$remainder}</a> ", $content);
			}
		}
	}
}
