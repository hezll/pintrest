<?php 
import('AdvModel');
class ChannelModel extends AdvModel {
	protected $_validate=array(
		array('cname','require','必须填写分类标题！',1),
		array('pid','number','必须填写选择父类！',1),
		array('oid','number','必须填写排序ID！',1),
		//array('cfile','require','必须填写分类别名！',1),
		array('cfile','','英文别名为空或已经存在,请重新设定！',1,'unique',1),
	);
}
?>