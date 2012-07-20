<?php
class DataObjectCommenting extends DataObjectDecorator {
	public function DataObjectComments($filter = "", $sort = "", $join = "", $limit = "", $containerClass = "DataObjectSet") {
		$thisFilter = "\"TargetID\" = {$this->owner->ID} AND \"TargetType\" = '{$this->owner->ClassName}'";
		if(strlen($filter)) {
			$filter = "{$thisFilter} AND {$filter}"
		} else $filter = $thisFilter;
		return DataObject::get("DataObjectComment", $filter, $sort, $join, $limit, $containerClass);
	}
}
