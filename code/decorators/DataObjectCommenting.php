<?php
class DataObjectCommenting extends DataObjectDecorator {
	public function DataObjectComments($filter = "", $sort = "Created DESC", $join = "", $limit = "", $containerClass = "DataObjectSet") {
		$thisFilter = "\"TargetID\" = '{$this->owner->ID}' AND \"TargetType\" = '{$this->owner->class}'";
		if(strlen($filter)) {
			$filter = "{$thisFilter} AND {$filter}";
		} else $filter = $thisFilter;
		return DataObject::get("DataObjectComment", $filter, $sort, $join, $limit, $containerClass);
	}
	
	public function DataObjectCommentsLimit($count, $start = 0) {
		return $this->DataObjectComments("", "Created DESC", "", "{$start}, {$count}");
	}
	
	public function DataObjectCommentsReverse() {
		return $this->DataObjectComments("", "Created ASC");
	}
	
	public function DataObjectCommentForm($enabled = true) {
		$form = singleton('DataObjectComments')->FormAddComment(null, $this->owner);
		if(!$enabled) {
			$fields = $form->Fields();
			if($fields) foreach($fields as $field)
				$field->setDisabled(true);
			foreach($form->actions as $action)
				$action->setReadOnly(true);
			$form->addExtraClass("disabled");
		}
		return $form;
	}
	
	public function onBeforeDelete() {
		$comments = $this->DataObjectComments();
		if($comments) foreach($comments as $comment)
			$comment->delete();
		parent::onBeforeDelete();
	}
}
