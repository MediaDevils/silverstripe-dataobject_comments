<?php
class DataObjectComments extends Controller {
	public function index($request) {
		$type = $request->getVar("TargetType");
		$id = $request->getVar("TargetID");
		if(is_string($type) && is_numeric($id)) {
			$target = DataObject::get_by_id($type, $id);
			if($target)
				return $this->render(array(
					"CommentForm" => $this->FormAddComment($request, $target)
				));
		}
		return '';
	}
	
	public function paginate($request) {
		$targetID = $request->getVar("TargetID");
		$targetType = $request->getVar("TargetType");
		
		$targetID = Convert::raw2sql($targetID);
		$targetType = Convert::raw2sql($targetType);
	
		$start = $request->getVar("start");
		$count = $request->getVar("count");
		
		$start = Convert::raw2sql($start);
		$count = Convert::raw2sql($count);
		
		if(!is_numeric($start) || !is_numeric($count) || !is_numeric($targetID) || empty($targetType))
			return null;
		
		$comments = DataObject::get("DataObjectComment", "\"TargetID\" = {$targetID} AND \"TargetType\" = '{$targetType}'", "Created DESC", "", "{$start}, {$count}");
		
		$rendered = array();
		if($comments) foreach($comments as $comment)
			$rendered[] = $comment->renderWith("DataObjectCommentLayout");
		else return '';
		
		return json_encode($rendered);
	}
	
	public function Link($action = null) {
		return get_class($this);
	}
	
	public function FormAddComment($request = null, DataObject $target = null) {
		$comment = singleton("DataObjectComment");
		$fields = $comment->getFrontEndFields();
		
		if($target) {
			$targetIDField = $fields->fieldByName("TargetID");
			$targetTypeField = $fields->fieldByName("TargetType");
			if($targetIDField && $targetTypeField) {
				$targetIDField->setValue($target->ID);
				$targetTypeField->setValue(get_class($target));
			}
		}
		
		$actions = new FieldSet(
			new FormAction("ActionAddComment", "Add Comment")
		);
		
		$required = $comment->getRequiredFields();
		
		return new Form($this, "FormAddComment", $fields, $actions, $required);
	}
	
	public function ActionAddComment($data, $form) {
		$comment = new DataObjectComment();
		$form->saveInto($comment);
		$comment->extend('handleAdd', $form);
		$comment->write();
		
		$target = $comment->Target();
		if($target && $target->hasMethod('DataObjectCommentAdd'))
			$target->DataObjectCommentAdd($comment);
			
		if($this->isAjax() && SSViewer::hasTemplate("DataObjectCommentLayout")) {
			return $comment->renderWith("DataObjectCommentLayout");
		} else return $this->redirectBack();
	}
	
	public function FormRemoveComment($request = null, DataObjectComment $comment = null) {
		$fields = new HiddenFieldSet(
			new HiddenField("CommentID", "Comment ID")
		);
		
		if($comment) {
			$commentIDField = $fields->fieldByName("CommentID");
			if($commentIDField)
				$commentIDField->setValue($comment->ID);
		}
		
		$actions = new FieldSet(
			new FormAction("ActionRemoveComment", "Remove Comment")
		);
		
		return new Form($this, "FormRemoveComment", $fields, $actions);
	}
	
	public function ActionRemoveComment($data, $form) {
		$allowed = true;
		
		$comment = DataObject::get_by_id("DataObjectComment", $form->dataFieldByName("CommentID")->Value());
		if($comment) {
			$comment->extend("handleRemove", $allowed);
			if($allowed) {
				$comment->delete();
			}
		}
		
		$this->redirectBack();
	}
}
