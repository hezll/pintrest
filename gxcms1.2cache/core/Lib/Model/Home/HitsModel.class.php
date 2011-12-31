<?php 
class HitsModel extends AdvModel {
	private $DB;
    function __construct(){
		$this->DB=D('video');
    }
    
	
/**
     +----------------------------------------------------------
     * 字段值延迟增长
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $field  字段名
     * @param mixed $condition  条件
     * @param integer $step  增长值
     * @param integer $lazyTime  延时时间(s)
     +----------------------------------------------------------
     * @return boolean
     +----------------------------------------------------------
     */    
    function setLazy($field,$condition='',$step=1,$lazyTime){
    	
      if(empty($condition) && isset($this->options['where']))
            $condition   =  $this->options['where'];
        if(empty($condition)) { // 没有条件不做任何更新
            return false;
        }
      if($lazyTime>0) {// 延迟写入
            $guid =  md5($this->name.'_'.$field.'_'.serialize($condition));
            if($step===0) return false;
            $step = $this->lazyWrite($guid,$step,$lazyTime);    
            if(false === $step ) return false; // 等待下次写入
        }
         return $step;
    }
	
}
?>