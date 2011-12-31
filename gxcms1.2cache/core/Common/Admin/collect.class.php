<?php 
/**
 * 
 * @name 采集公共类
 *
 */
class collect{

	//过滤规则  checkboxs
    function checkbox($array = array(), $id = '', $str = '', $defaultvalue = '', $width = 0, $field = '') {
		$string = '';
		$id = trim($id);
		if($id != '') $id = strpos($id, ',') ? explode(',', $id) : array($id);
		if($defaultvalue) $string .= '<input type="hidden" '.$str.' value="-99">';
		$i = 1;
		foreach($array as $key=>$value) {
			$key = trim($key);
			$checked = ($id && in_array($key, $id)) ? 'checked' : '';
			if($width) $string .= '<label class="ib" style="width:'.$width.'px">';
			$string .= '<input type="checkbox" '.$str.' id="'.$field.'_'.$i.'" '.$checked.' value="'.htmlspecialchars($key).'"> '.htmlspecialchars($value);
			if($width) $string .= '</label>';
			$i++;
		}
		return $string;
	}
	
	/**
	 * URL地址检查
	 * @param string $url      需要检查的URL
	 * @param string $baseurl  基本URL
	 * @param array $config    配置信息
	 */
     function url_check($url, $baseurl, $config) {
		$urlinfo = parse_url($baseurl);
		
		$baseurl = $urlinfo['scheme'].'://'.$urlinfo['host'].(substr($urlinfo['path'], -1, 1) === '/' ? substr($urlinfo['path'], 0, -1) : str_replace('\\', '/', dirname($urlinfo['path']))).'/';
		if (strpos($url, '://') === false) {
			if ($url[0] == '/') {
				$url = $urlinfo['scheme'].'://'.$urlinfo['host'].$url;
			} else {
				if ($config['page_base']) {
					$url = $config['page_base'].$url;
				} else {
					$url = $baseurl.$url;
				}
			}
		}
		return $url;
	}
	
	
	/**
		 * 获取远程HTML
		 * @param string $url    获取地址
		 * @param array $config  配置
		 */
		 function get_html($url, &$config) {
		 	if(!get_headers($url)) return false;//快速判断是否能连接到$url
			if (!empty($url) && $html = @file_get_contents($url)) {
				if ($syscharset != $config['sourcecharset'] && $config['sourcetype'] != 4) {
					$html = iconv($config['sourcecharset'], 'utf-8', $html);
				}
				return $html;
			} else {
				return false;
			}
	}
	
  /**
	 * HTML切取
	 * @param string $html    要进入切取的HTML代码
	 * @param string $start   开始
	 * @param string $end     结束
	 */
     function cut_html($html, $start, $end) {
		if (empty($html)) return false;
		$html  = str_replace(array("\r", "\n"), "", $html);
		$start = str_replace(array("\r", "\n"), "", $start);
		$end   = str_replace(array("\r", "\n"), "", $end);
		$start = stripslashes($start);
		$end   = stripslashes($end);
		if(!empty($start)) $html  = explode(trim($start), $html); 
		if(!empty($end) && is_array($html)){ 
		$html = explode(trim($end), $html[1]);
		return $html[0];
		}else{
			return $html;
		}
	}
	
	
	/**
	 * 替换采集内容
	 * @param $html 采集内容规则
	 */
	 function replace_sg($html) {
		$list = explode(C('cms_col_content'), $html);
		if (is_array($list)) foreach ($list as $k=>$v) {
			$list[$k] = str_replace(array("\r", "\n"), '', trim($v));
			//$list[$k] = self::str_replace_all($v);
		}
		return $list;
	}
	
   /**
	 * 过滤代码
	 * @param string $html  HTML代码
	 * @param array $config 过滤配置
	 */
	 function replace_item($html, $config) {
		if (empty($config)) return $html;
		$config = explode("\n", $config);
		$patterns = $replace = array();
		$p = 0;
		foreach ($config as $k=>$v) {
			if (empty($v)) continue;
			$c = explode('[|]', $v);
			$patterns[$k] = '/'.str_replace('/', '\/', $c[0]).'/i';
			//$patterns[$k] = '/'.self::str_replace_all($c[0]).'/i';
			$replace[$k] = $c[1];
			$p = 1;
		}
		return $p ? @preg_replace($patterns, $replace, $html) : false;
	}
	
	/**
	 * 返回经addslashes处理过的字符串或数组
	 * @param $string 需要处理的字符串或数组
	 * @return mixed
	 */
	static function Doaddslashes($str){
		if(!is_array($str)) return addslashes($str);
		foreach($str as $key => $val){		
		 $str[$key] =self::Doaddslashes($val);
		}
		return $str;
	}
	
	
		/**
		 * 返回经stripslashes处理过的字符串或数组
		 * @param $string 需要处理的字符串或数组
		 * @return mixed
		 */
	 static function Dostripslashes($str) {
			if(!is_array($str)) return stripslashes($str);
			foreach($str as $key => $val) {
				$str[$key] = self::Dostripslashes($val);
			}
			return $str;
		}
	
		/**
		* 将字符串转换为数组
		*
		* @param	string	$data	字符串
		* @return	array	返回数组格式，如果，data为空，则返回空数组
		*/
		static function string2array($data) {
			if(is_array($data)) return $data;
			if($data == '') return array();
			@eval("\$array = $data;");
			return $array;
		}
		
		/**
		* 将数组转换为字符串
		*
		* @param	array	$data		数组
		* @param	bool	$isformdata	如果为0，则不使用Dostripslashes处理，可选参数，默认为1
		* @return	string	返回字符串，如果，data为空，则返回空
		*/
		static function array2string($data, $isformdata = 1) {
			if($data == '') return '';
			if($isformdata) $data = self::Dostripslashes($data);
			return addslashes(var_export($data, TRUE));
		}
		
		
	/**
	 * 转换图片地址为绝对路径，为下载做准备。
	 * @param array $out 图片地址
	 */
    function download_img($old, $out) {
		if (!empty($old) && !empty($out) && strpos($out, '://') === false) {
			return str_replace($out, self::url_check($out, $url, $config), $old);
		} else {
			return $old;
		}
	}
	/**
	 * 特殊字符转换( ) [ ] . * / ? + ^ | $
	 * @param sring $data
	 */
	function str_replace_all($data){
		$out=str_replace(array('(',')','[',']','.','*','/','?','+','$'), array('\(','\)','\[','\]','\.','.*?','\/','\?','\+','\$'), $data);
		return $out;
	}
	
	
    /**
	 * 智能分类-通过名称获取对应系统栏目信息
	 * @param $cname
	 * @param $type
	 * @return id  若为空 返回false
	 */
	function get_syschannel_id($cname,$type='id'){
	    $arr = list_search(F('_gxcms/channel'),'cname='.$cname);
		if (is_array($arr) && !empty($arr)) {
			return $arr[0][$type];
		}else{
		    return false;
		}
	}
	
	/**
	 * 智能分类-通过名称获取对应栏目信息
	 * @param $cname
	 * @param $type
	 * @return id  若为空 返回false
	 */
	function get_channel_id($cname,$type='id'){
	    $arr = list_search(F('_gxcms/channel'),'cname='.$cname);
		if (is_array($arr) && !empty($arr)) {
			return $arr[0][$type];
		}else{
		$arr = list_search(F('_gxcms/channel'),"cname=/".mb_substr($cname, 0, 2, 'utf-8')."([\s\S]*?)/");
		if (is_array($arr) && !empty($arr)) {
			return $arr[0][$type];
		}else{
		    return false;
		}
		}
	}
	
/**
	 * 智能分类-通过名称获取对应自定义分类信息
	 * @param $cname
	 * @param $type 系统栏目id
	 * @return id  若为空 返回false
	 */
	function get_Autochannel_id($cname,$nid,$type='reid'){
	    $arr = list_search(F('_gxcms/Autochannel'),'cname='.$cname.'_'.$nid);
		if (is_array($arr) && !empty($arr)) {
			return $arr[0][$type];
		}else{
		$arr = list_search(F('_gxcms/Autochannel'),'cname='.$cname.'_0');
		if (is_array($arr) && !empty($arr)) {
			return $arr[0][$type];
		}else{
		    return false;
		}
		}
	}
	
	
	
    /**
	 * 采集未匹配成功 的---自定义分类转换信息
	 * @param string $cname
	 * @return array id&nid  若为空 返回false
	 */
	function get_channel999_id($cname,$nid='0'){
		
		if($nid=='0'){
			$str='cname=/'.$cname."_([\s\S]*?)/";
		}else{
			$str='cname='.$cname.'_'.$nid;
			
		}
	    $arr = list_search(F('_gxcms/channel_999'),$str);
		if (is_array($arr) && !empty($arr)) {
			return $arr;
		}else{
		    return false;
		}
	}
	
	
/**
 * 通过节点ID获取名称
 * @param  int    $nid
 * @param  string $type
 * @return 
 */
function get_node_name($nid,$type='name'){
    $arr = list_search(F('_gxcms/ColNode'),'id='.$nid);
	if (is_array($arr)) {
		return $arr[0][$type];
	}else{
	    return $nid;
	}
}
	
	
}
?>