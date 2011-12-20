<?php 
/**
 * @name    重复或无效数据检测模块
 * @package GXCMS.Administrator
 * @link    www.gxcms.com
 */
class DatacheckModel extends Model {
    private $VideoDB;
    function __construct(){
		$this->VideoDB=D('video');
		import("ORG.Io.Dir");
    }
    function GetParam(){
    	$Get=getReq($_REQUEST,array(
    	            'len'    =>'int',
    	            'cid'    =>'int',
    	            'status' =>'int',
    	            'keyword'=>'string',
    	            'type'   =>'string',//排序字段
    	            'order'  =>'string',  
    	     ));    
		$Get['keyword']= urldecode(trim($Get['keyword']));
        return $Get;     
    }
    
    //search condition
    function SearchCon($Get){
		if ($Get['cid']) {
			if(get_channel_son($Get['cid'])){
				$where['cid']= $Get['cid'];
			}else{
				$where['cid']= get_channel_sqlin($Get['cid']);
			}
		}
		if ($Get['status'] || $Get['status']==='0') {
			$where['status']   = array('eq',intval($Get['status']));
		}
	
		if ($Get['keyword']) {
			$search['title']   = array('like','%'.$Get['keyword'].'%');
			$search['intro']   = array('like','%'.$Get['keyword'].'%');
			$search['actor']   = array('like','%'.$Get['keyword'].'%');
			$search['director']= array('like','%'.$Get['keyword'].'%');
			$search['_logic']  = 'or';
			$where['_complex'] = $search;
		}
		//
		return $where;
    }
    /**
     * 影片重名检测
     * @param  int $len
     * @return array
     */
    function RepeatCheck($Get){	
    	$len=$Get['len'];
        //先查询$len内不同影片的title
        $arr=$this->VideoDB->field('title')->Group("Left(title,$len)")->Having('count(*)>1')->select();
        foreach($arr as $key=>$val){
        	$arrTitle[].=$val['title'];
        }
        //$where['status']=array('neq',-1);
        $where=$this->SearchCon($Get);
        $where["left(title, $len)"]=array('in',$arrTitle);
        
        $video_count = $this->VideoDB->where($where)->count('id');
		$video_page  = !empty($_GET['p'])?intval($_GET['p']):1;
		$video_page  = get_cms_page_max($video_count,C('web_admin_pagenum'),$video_page);
		$video_url   = U('Admin-Datacheck/VideoCheck',array('check_sub'=>'ok','len'=>urlencode($len),'p'=>''),false,false);
		$pagelist    = get_cms_page($video_count,C('web_admin_pagenum'),$video_page,$video_url,'部影片');
		$_SESSION['video_repurl'] = $video_url.$video_page;
		
		//order by 
		$video['type']  = !empty($_GET['type'])?$_GET['type']:'title';
		$video['order'] = !empty($_GET['order'])?$_GET['order']:'desc';
		$order          = $video["type"].' '.$video['order'];
		
        $VResult=$this->VideoDB->field('id,title,cid,serial,addtime,hits,stars,status,picurl')->where($where)->order($order)->limit(C('web_admin_pagenum'))->page($video_page)->select();
        foreach($VResult as $key=>$val){
			$VResult[$key]['cname']      = get_channel_name($VResult[$key]['cid']);
			$VResult[$key]['channelurl'] = U('Admin-Video/Show',array('cid'=>$VResult[$key]['cid']),false,false);
			$VResult[$key]['videourl']   = get_read_url('video',$VResult[$key]['id'],$VResult[$key]['cid']);
			$VResult[$key]['stararr']    = get_star_arr($VResult[$key]['stars']);
		}
		
	    return array(
	                'vresult' =>$VResult,
	                'pagelist'=>$pagelist,
	                'len'     =>$len, 
	                'order'   =>$order,
	                 'cid'    =>$Get['cid'],
	                 );
       
    }
    
    
    /**
     * 获取目录信息
     * @param $path
     */
    function ImgList($path){
    	$dir = new Dir($path);
    	if($dir->isEmpty($path)){
    	 return false;
    	}
		$dirlist = $dir->toArray();
		return $dirlist;
    }
    
    /**
     * 查询数据库中$filepath是否存在
     * @param   string $filepath
     * @return  boolean true 删除成功
     */
    function DataCheck($filepath){
        if(!is_dir($filepath)){
		   $filedir=substr($filepath,strpos($filepath,'video'));
		   $filedir=str_replace('\\','/',$filedir);
		   	
		   $arr=$this->VideoDB->field('id')->where("picurl='".$filedir."'")->find();
		   if(!is_array($arr)){
		   @unlink($filepath);
		   $filepath=iconv("gb2312","UTF-8",$filepath);
		   $this->error= '删除无效图片'.$filepath;
		   return true;
		   }
		}
		return false;
    }
    
    /**
     * 无效图片清除
     */
    function InvalidImg(){	
    	header("Content-type: text/html; charset=utf-8");
	    $path="./".C('upload_path').'/video';	
		$dirlist = $this->ImgList($path);
        if (!$dirlist){
		  $this->error=$path.'目录不存在，可能是尚未保存图片到本地!';
		  return false;
		}
        if (empty($dirlist)){
		  $this->error=$path.'目录下没有图片!';
		  return false;
		}
		
		
		foreach($dirlist as $key=>$val){//文件夹list
		   if(is_dir($val['pathname'])){//子目录
		     $sublist = $this->ImgList($val['pathname']);//imagelist
		     if(!empty($sublist) && is_array($sublist)){ 
		        foreach($sublist as $k=>$v){
		     	if($this->DataCheck($v['pathname'])){
		     	echo '<div style=font-size:12px;margin:5px;>'.$this->getError().'</div>';
		     	}
		        }
		     }
		  }else{//video目录
		        if($this->DataCheck($val['pathname'])){
		     	echo '<div>'.$this->getError().'</div>';
		     	}
			}
		}
	
	    closedir($path);
	    return true;
    }
}
?>   