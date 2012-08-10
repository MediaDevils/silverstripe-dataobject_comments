<?php
DataObject::add_extension("DataObject", "DataObjectCommenting");
DataObject::add_extension("DataObjectComment", "DataObjectCommentBasic");
DataObject::add_extension("DataObjectComment", "DataObjectCommentPermissions");
if(class_exists("oEmbed"))
	DataObject::add_extension("DataObjectComment", "DataObjectCommentoEmbed");
