<?php
class DataObjectCommenting extends DataExtension {
	public function DataObjectComments($filter = "", $sort = "Created DESC", $join = "", $limit = "") {
		$thisFilter = "\"TargetID\" = '{$this->owner->ID}' AND \"TargetType\" = '{$this->owner->class}'";
		if(strlen($filter)) {
			$filter = "{$thisFilter} AND {$filter}";
		} else $filter = $thisFilter;
		
		$list = DataObjectComment::get();
		if(!empty($filter)) $list = $list->filter($filter);
		if(!empty($sort)) $list = $list->sort($sort);
		if(!empty($join)) $list = $list->join($join);
		if(!empty($limit)) $list = $list->limit($limit);
		
		return $list;
	}
	
	public function DataObjectCommentsLimit($count, $start = 0) {
		return $this->DataObjectComments("", "Created DESC", "", "{$start}, {$count}");
	}
	
	public function DataObjectCommentsReverse() {
		return $this->DataObjectComments("", "Created ASC");
	}
	
	public function DataObjectCommentForm() {
		return singleton('DataObjectComments')->FormAddComment(null, $this->owner);
	}
	
	public function onBeforeDelete() {
		$comments = $this->DataObjectComments();
		if($comments) foreach($comments as $comment)
			$comment->delete();
		parent::onBeforeDelete();
	}
}
