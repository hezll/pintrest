<?php 
import('AdvModel');
class LinkModel extends AdvModel {
	protected $_validate=array(
		array('title','require','友情链接名称必须填写！',1,'',1),
		array('url','require','友情链接网址必须填写！',1,'',1),
	);
}
?>