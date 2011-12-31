<?php
/**
 * @name    缓存模块
 * @package GXCMS.Administrator
 * @link    www.gxcms.com
 */
class CacheAction extends AdminAction{
	// 列表
    public function show(){
		$this->create_channel();
        $this->display('./views/admin/cache_show.html');
    }
	// 删除
    public function del(){
		import("ORG.Io.Dir");
		$dir = new Dir;
		$this->create_channel();
		@unlink('./temp/~app.php');
		@unlink('./temp/~runtime.php');
		if(is_dir('./temp/Data/_fields')){$dir->del('./temp/Data/_fields');}
		if(is_dir('./temp/Temp')){$dir->delDir('./temp/Temp');}
		if(is_dir('./temp/Cache')){$dir->delDir('./temp/Cache');}
		if(is_dir('./temp/Logs')){$dir->delDir('./temp/Logs');}
		echo('[清除成功]');
    }
	// 删除静态缓存
	public function delhtml(){
	    import("ORG.Io.Dir");
		$dir = new Dir;
		$cache=$_POST;//$dir->delDir('./Html');
		$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Cache/Show');
		if($cache['index']){
			@unlink(HTML_PATH.'index'.C('html_file_suffix'));
			$this->success('首页静态缓存文件清除成功！');
		}elseif($cache['vodshow']){
			if(is_dir(HTML_PATH.'Video_lists')){$dir->delDir(HTML_PATH.'Video_lists');}
			$this->success('影视列表页静态缓存文件清除成功！');		    
		}elseif($cache['vodread']){
			if(is_dir(HTML_PATH.'Video_detail')){$dir->delDir(HTML_PATH.'Video_detail');}
			$this->success('影视内容页静态缓存文件清除成功！');		    
		}elseif($cache['vodplay']){
			if(is_dir(HTML_PATH.'Video_play')){$dir->delDir(HTML_PATH.'Video_play');}
			$this->success('影视播放页静态缓存文件清除成功！');		    
		}elseif($cache['newsshow']){
			if(is_dir(HTML_PATH.'Info_lists')){$dir->delDir(HTML_PATH.'Info_lists');}
			$this->success('新闻列表页静态缓存文件清除成功！');	    
		}elseif($cache['newsread']){
			if(is_dir(HTML_PATH.'Info_detail')){$dir->delDir(HTML_PATH.'Info_detail');}
			$this->success('新闻内容页静态缓存文件清除成功！');	    
		}elseif($cache['ajaxshow']){
		    if(is_dir(HTML_PATH.'My_show')){$dir->delDir(HTML_PATH.'My_show');}	
			$this->success('自定义模板静态缓存文件清除成功！');	    
		}else{
		    @unlink(HTML_PATH.'index'.C('html_file_suffix'));
			if(is_dir(HTML_PATH.'Video_lists')){$dir->delDir(HTML_PATH.'Video_lists');}
			if(is_dir(HTML_PATH.'Video_detail')){$dir->delDir(HTML_PATH.'Video_detail');}
			if(is_dir(HTML_PATH.'Video_play')){$dir->delDir(HTML_PATH.'Video_play');}
			if(is_dir(HTML_PATH.'Info_lists')){$dir->delDir(HTML_PATH.'Info_lists');}
			if(is_dir(HTML_PATH.'Info_detail')){$dir->delDir(HTML_PATH.'Info_detail');}
			if(is_dir(HTML_PATH.'My_show')){$dir->delDir(HTML_PATH.'My_show');}		
			$this->success('所有静态缓存文件更新成功！');		
		}
	}					
}
?>