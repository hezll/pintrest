<?php 
import('AdvModel');
class AdsenseModel extends AdvModel {
	//stripslashes、htmlentities、htmlspecialchars
	
	protected $_validate=array(
		array('title','require','广告标识必须填写！',1,'',1),
		array('title','','该广告标识已经存在,请重新填写一个广告标识！',1,'unique',1),
	);
	
	protected $_auto=array(
		array('title','trim',3,'function'),
		array('content','trim',3,'function'),
		array('content','stripslashes',3,'function'),
	);
}
?>