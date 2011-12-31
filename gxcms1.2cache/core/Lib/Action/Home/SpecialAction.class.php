<?php
class SpecialAction extends HomeAction{
    public function index(){
		$this->show();
	}
    //专题列表
    public function lists(){
		//获取地址栏参数并读取栏目缓存信息
		$page  = !empty($_GET['p']) ? intval($_GET['p']) : 1;
		$order = !empty($_GET['order']) ? get_replace_order($_GET['order']) : 'addtime';
		$list  = F('_gxcms/channel');$channel['limit'] = $list[999]['special'];
		//查询本类及小类共多少条数据
		$where['status'] = array('eq',1);
		$rs = M('special');
		$count = $rs->where($where)->count('id');
		//生成翻页链接参数(支持排序所以强制栏目为动态模式)
		C('url_html_channel',0);
		if ($order != 'addtime') {
			$arrurl['order'] = $order;
		}
		//组合分页信息
		$totalpages = ceil($count/$channel['limit']);
		if($page > $totalpages){ 
			$page = $totalpages; 
		}
		$pages  = '共'.$count.'篇专题&nbsp;当前:'.$page.'/'.$totalpages.'页&nbsp;';
		$pages .= get_cms_page_css($page,$totalpages,C('web_home_pagenum'),get_show_url('special',$arrurl,2),false);
		//整理栏目前台标签数组变量
		$channel['order'] = $order;
		$channel['page']  = $page;
		$channel['pages'] = $pages;
		$channel['count'] = $count;
		$channel['navtitle'] = '<a href="'.C('web_path').'">首页</a> &gt; <span>专题列表</span>';
		if ($page > 1) {
			$channel['webtitle'] = '专题列表-第'.$page.'页-'.C('web_name');
		}else{	
			$channel['webtitle'] = '专题列表-'.C('web_name');
		}
		//先给bdlist标签传值后再输出模板
		C('bdlist_page',$page);
		$this->assign($channel);
		$this->display('special_list');
    }
	//读取专题内容
    public function detail(){
		$where['id']     = $_GET['id'];
		$where['status'] = array('eq',1);
		$rs = M("Special");
		$array = $rs->where($where)->find();
		if($array){
			$array = $this->tags_special_read($array);//变量赋值
			$this->assign($array);
			$this->display('special_detail');
		}else{
			$this->assign("jumpUrl",C('web_path'));
			$this->error('此专题已经删除或未开放,请选择观看其它节目！');
		}
    }			
}
?>