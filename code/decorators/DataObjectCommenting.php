<?php
class DataObjectCommenting extends DataObjectDecorator {
	public function DataObjectComments($filter = "", $sort = "Created DESC", $join = "", $limit = "", $containerClass = "DataObjectSet") {
		$thisFilter = "\"TargetID\" = '{$this->owner->ID}' AND \"TargetType\" = '{$this->owner->ClassName}'";
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
	
	public function DataObjectCommentForm() {
		return singleton('DataObjectComments')->FormAddComment(null, $this->owner);
	}
}
