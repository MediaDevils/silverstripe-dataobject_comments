<?php
class DataObjectCommentFacebook extends DataObjectDecorator {
	public function updateFrontEndFields(&$fields) {
		if($this->isFacebookMember()) {
			$field = $fields->insertAfter(new CheckboxField("PostToFacebook", "Post To Facebook"), "Content");
			$this->owner->extend("updatePostToFacebookCheckbox", $field);
		}
		
		return $fields;
	}
	
	public function handleAfterAdd(Form &$form) {
		if(!$this->isFacebookMember())
			return;
		if(!($facebook = $this->getFacebook()))
			return;
	
		$post = false;
		$this->owner->extend("updatePostToFacebook", $post);
		
		$postByForm = $form->dataFieldByName("PostToFacebook")->dataValue();
		
		$post = ($post || $postByForm);
		
		if(!$post)
			return;
		
		$message = $form->dataFieldByName("Content")->dataValue();
		
		$parameters = array();
		if($link = Director::absoluteBaseURL().$this->owner->Link())
			$parameters["link"] = $link;
		
		$parameters["caption"] = $message;
		
		$this->owner->extend("updatePostToFacebookParams", $parameters);
		
		$facebook->api("/me/feed", "post", $parameters);
	}
	
	protected function isFacebookMember() {
		$controller = Controller::curr();
		$facebookMember = $controller->CurrentFacebookMember;
		
		return $facebookMember?true:false;
	}
	
	protected function getFacebook() {
		$controller = Controller::curr();
		$facebook = $controller->Facebook;
		return $facebook;
	}
}
