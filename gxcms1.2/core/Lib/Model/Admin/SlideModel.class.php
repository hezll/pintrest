<?php 
import('AdvModel');
class SlideModel extends AdvModel {
	protected $_validate=array(
		array('title','require','幻灯名称必须填写！',1,'',3),
		array('link','require','链接地址必须填写！',1,'',3),
	);
	
	protected $_auto=array(
		array('title','trim',3,'function'),
		array('link','trim',3,'function'),
		array('content','trim',3,'function'),
	);
}
?>