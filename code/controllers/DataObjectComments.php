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
	
	public function Link($action = null) {
		return get_class($this);
	}
	
	public function FormAddComment($request = null, DataObject $target = null) {
		$fields = singleton("DataObjectComment")->getFrontEndFields();
		
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
		
		return new Form($this, "FormAddComment", $fields, $actions);
	}
	
	public function ActionAddComment($data, $form) {
		$comment = new DataObjectComment();
		$form->saveInto($comment);
		$comment->extend('handleComment', $form);
		$comment->write();
		
		$this->redirectBack();
	}
}
