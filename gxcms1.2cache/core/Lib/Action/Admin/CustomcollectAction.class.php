<?php
/**
 * @name    自定义采集模块
 * @package GXCMS.Administrator
 * @link    www.gxcms.com
 */
class CustomcollectAction extends AdminAction{
	private $CModel;
	private $RModel;
	public function _initialize(){
		parent::_initialize();
	 	C('TMPL_FILE_NAME','./views/collection/..');
	 	$this->CModel =D('Admin.CustomCollect');
	 	$this->RModel =D('Admin.ColRun');
	 }
 
    public function Index(){
		exit;
	}
	
    /**
	 * add node
	 */

	public function Add() {
		header("Cache-control: private");
		if(isset($_POST['dosubmit'])) {
			$Get =$this->CModel->GetParam();//getparam
			if(!$this->CModel->CheckParam($Get))  $this->error($this->CModel->getError());//checkparam
			if($nid=$this->CModel->Save($Get)){//save
				$this->assign("jumpUrl",C('cms_admin').'?s=Admin/CustomCollect/ListShow');
				//$this->assign("jumpUrl",C('cms_admin').'?s=Admin/CustomCollect/ColRun/action/demo/type/all/nid/'.$nid);
				if(!empty($Get['nid'])) {
					$this->success('修改成功！');
					$this->success('跳转到预览页面');
				}else{$this->success('添加成功！');}
			}else{
				$this->error('操作失败！');
			}

		} else {
		   if(!empty($_GET['nid'])){//edit page
		    $this->assign($this->CModel->DataShow($_GET['nid']));
		   }
			$ArrBase=$this->CModel->BaseArr();
			$this->assign('base',$ArrBase);
			$this->assign('files',$this->CModel->FilterRules());//内容过滤规则（js调用）
			$this->assign('channel_tree',F('_gxcms/channeltree'));
			$this->display('node_form');
		}
		
	}
	
	public function Manage(){
		$action=$_GET['action'];
		$this->assign("jumpUrl",C('cms_admin').'?s=Admin/CustomCollect/ListShow');
		switch($action){
			case 'copy':  
						  $ArrCopy=$this->CModel->ActionCheck();
						  if($ArrCopy){
							   if(isset($_POST['dosubmit'])){	
							  		$Result=$this->CModel->Copy($ArrCopy);
							  		if($Result){
							  		$this->success('复制成功！');
							  		}else{$this->error('操作失败！');}
							  	}else{
							  		$this->assign('name',$ArrCopy['name']);
							  		$this->assign('nid', $ArrCopy['id']);
							  		$this->display('node_cp');
							  	}
						  }else{
						  $this->error('参数错误！');
						  }
						 break;
			case 'txtExport':
				           if($_POST['codedata']){
							header("Content-type: application/octet-stream");
		                    header("Content-Disposition: attachment; filename=coll_".$_GET['nid'].'.txt');
		                    echo $_POST['codedata'];
							}
						   break;			 
			case 'export': 
						  if($Data=$this->CModel->ExpCode()){ 
							$this->assign('data',$Data);
							$this->assign('nid',$_GET['nid']);	
							$this->display('export');
							}
						   break;
			case 'import': 
						   if(isset($_POST['submit'])){ 
					          $Save=$this->CModel->SaveImport();//save
					          if($Save) {
					          $this->success('导入成功！');
					          }else{
					          $this->assign("jumpUrl",C('cms_admin').'?s=Admin/CustomCollect/Manage/action/import');
					          if($this->CModel->getError()){
					          $this->error($this->CModel->getError());
					          }else{ $this->error('无法识别的编码！');}
					          }   
							}else{
							  $this->display('export');
							}
						   break;    
			case 'del':    if($this->CModel->Del())     $this->success('删除成功！');break;
			case 'delall': if($this->CModel->DelAll()) $this->success('批量删除成功！');break;
			default:echo 'Request error'; break;
		}
		
	}

	
	
    //URL配置结果测试
	public function TestUrl() {
		$ArrUrl=$this->CModel->GetUrl();
		if(!$ArrUrl){
		 //$this->error($this->CModel->getError());
		}else{
		$this->assign('ArrUrl',$ArrUrl);
		}
		$this->assign('jumpUrl','?s=Admin/CustomCollect/Add');
		$this->display('url_list');
	}
	
	
	//自定义采集节点管理   
	public function ListShow(){
		$ArrNode=$this->CModel->ShowList();
		$this->assign('cache', F('_gxcms/ColCache'));
		$this->assign('ArrList', $ArrNode['node']);
		$this->assign('pagelist',$ArrNode['pagelist']);
		$this->display('node_list');
	}
	
	//采集节点测试功能
	public function ColTest(){
		$Get =$this->CModel->GetParam();
		if(!$this->CModel->CheckParam($Get))  $this->error($this->CModel->getError());//checkparam
		//test
		$ArrUrl=$this->RModel->CoUrl($Get);//video urls
		$Con =$this->RModel->Contest($ArrUrl['url'][0],$Get);
		$this->assign('url',$ArrUrl['url'][0]['url']);
		$this->assign('con',$Con);
		$this->display('col_url');	
	}
	
	//执行采集   test/demo/real
	public function ColRun(){
		header("Content-type: text/html; charset=utf-8");
		$Param = $this->RModel->GetParam();
		$Data  = $this->RModel->Check($Param);
		if(!$Data) $this->error($this->RModel->getError());

		$this->assign('jumpUrl','?s=Admin/CustomCollect/ListShow');
		
	    if($Param['action']==='real' && $Param['type']==='content'){//真实内容采集
		    $ColResult =$this->RModel->Collecting($Data);
		    if($ColResult){
		       if($ColResult=='1'){
		       $this->success("采集完成! 自动跳转到采集节点管理");exit;
		       }
		       $this->assign('video',$ColResult);		
		       $this->display('col_cont');
		    }else{
		       $this->error($this->RModel->getError());
		     }
	    }
	    
		$ArrUrl=$this->RModel->CoUrl($Data);//video urls
		if(!$ArrUrl) $this->error($this->RModel->getError());
	
		if($Param['action']==='demo'){
		 	 $Con =$this->RModel->Contest($ArrUrl['url'][0],$Data);
		 	 $this->assign('url',$ArrUrl['url'][0]['url']);
		 	 $this->assign('con',$Con);
		}
		
		if($Param['type']==='all'){
		   $this->assign('ArrUrl',$ArrUrl);
		   $this->display('col_url');
		}
	
	}
	
	//采集影片
	public function ColVideo(){
		$Search=$this->RModel->ColVideoSearch();
		$Arr   =$this->RModel->ColVideoList($Search);
		$this->assign('Arr',$Arr);
		$this->assign('nodetree', F('_gxcms/ColNode'));//采集节点项目list
		$this->display('col_video_list');
	}
   
	/**
	 * 采集影片入库管理
	 */
	public function ColManage(){
	header("Content-type: text/html; charset=utf-8");
	  if(empty($_GET['act'])) {
	  $this->error('参数 有误!');
	  }else{
	  	$act=$_GET['act'];
	  	if($act=='inflow' || $act=='allinflow' || $act=='today' || $act=='allunused'){//入库
	  	  $Result=$this->RModel->Inflow($act);
	  	  if($Result){
	  	  $this->assign('result',$Result);
		  $this->display('col_video_save');	
		 // $this->success('成功入库影片!');   
		  }else{
		  $this->error($this->RModel->getError());
		  }
	  	}else if($act=='delselect' || $act=='delall' || $act=='delonly'){
	  	  if($this->RModel->Del($act)){
		  $this->success('删除影片成功!');   
		  }else{
		  $this->error($this->RModel->getError());
		  }
	  	}
	  	
	  }
	}
	
	//自定义分类转换
	public function AutoChannel(){
	  if(!empty($_GET['action'])){
	    if($this->CModel->ChannelManage($_GET['action'])) {
        $this->assign("jumpUrl",C('cms_admin').'?s=Admin/CustomCollect/AutoChannel');
        $this->success('操作成功!');
	    }else{$this->error($this->CModel->getError());}
	  }	else{
	    $search=$this->CModel->ChannelSearch();	
	    $this->assign('nodetree', F('_gxcms/ColNode'));
	    $this->assign('list', $this->CModel->ChannelList($search));
	    $this->assign('channel_tree',F('_gxcms/channeltree'));
	    $this->display('col_class');	
	  }
	}
	

}
?>