<?php
/**
 * @name    数据库操作模块
 * @package GXCMS.Administrator
 * @link    www.gxcms.com
 */
class DataAction extends AdminAction{
	// 数据库备份展示	
    public function showtable(){
		$id = $_GET['id'];
		$rs = new Model();
		$list = $rs->query('SHOW TABLES FROM '.C('db_name'));
		$tablearr = array();
        foreach ($list as $key => $val) {
            $tablearr[$key] = current($val);
        }
		$this->assign('list_table',$tablearr);
		if($id){
			unset($tablearr[C('db_prefix').'_master']);
			$this->display('./views/admin/db_'.$id.'.html');
		}else{
			$this->display('./views/admin/db_table.html');
		}
    }
	//处理数据库备份
	public function insertsql(){
		if(empty($_POST['ids'])){
			$this->error('请选择需要备份的数据库表!');
		}	
		$filesize = intval($_POST['filesize']);
		if ($filesize<1) {
			$this->error('出错了,请为分卷大小设置一个整数值!');
		}
		$file = RUNTIME_PATH.'DataBack/';
		$random = mt_rand(1000, 9999);
		$sql = ''; 
		$p = 1;
		foreach($_POST['ids'] as $table){
			$rs = D('Admin.'.str_replace(C('db_prefix'),'',$table));
			$array = $rs->select();
			$sql.= "TRUNCATE TABLE `$table`;\n";
			foreach($array as $value){
				$sql.= $this->getinsertsql($table, $value);
				if (strlen($sql) >= $filesize*1000) {
					$filename = $file.date('Ymd').'_'.$random.'_'.$p.'.sql';
					write_file($filename,$sql);
					$p++;
					$sql='';
				}
			}
		}
		if(!empty($sql)){
			$filename = $file.date('Ymd').'_'.$random.'_'.$p.'.sql';
			write_file($filename,$sql);
		}
		$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Data/Showback');
		$this->success('数据库分卷备份已完成,共分成'.$p.'个sql文件存放!');
    }
	//生成SQL备份语句
	public function getinsertsql($table, $row){
		$sql = "INSERT INTO `{$table}` VALUES ("; 
		$values = array(); 
		foreach ($row as $value) { 
			$values[] = "'" . mysql_real_escape_string($value) . "'"; 
		} 
		$sql .= implode(', ', $values) . ");\n"; 
		return $sql;
	}
	//展示还原
    public function showback(){
		$filepath = RUNTIME_PATH.'DataBack/*.sql';
		$filearr = glob($filepath);
		if (!empty($filearr)) {
			foreach($filearr as $k=>$sqlfile){
				preg_match("/([0-9]{8}_[0-9a-z]{4}_)([0-9]+)\.sql/i",basename($sqlfile),$num);
				$backup[$k]['filename'] = basename($sqlfile);
				$backup[$k]['filesize'] = round(filesize($sqlfile)/(1024*1024), 2);
				$backup[$k]['maketime'] = date('Y-m-d H:i:s', filemtime($sqlfile));
				$backup[$k]['pre']    = $num[1];
				$backup[$k]['number'] = $num[2];
				$backup[$k]['path']   = RUNTIME_PATH.'DataBack/';
			}
			$this->assign('list_backup',$backup);
        	$this->display('./views/admin/db_backup.html');
		}else{
			$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Data/Showtable');
			$this->error('没有检测到备份文件,请先备份或上传备份文件到'.RUNTIME_PATH.'DataBack/');
		}
    }
	//导入还原
	public function backin(){
		$rs  = new Model();
		$pre      = $_GET['id'];
		$fileid   = $_GET['fileid'] ? intval($_GET['fileid']) : 1;
		$filename = $pre.$fileid.'.sql';
		$filepath = RUNTIME_PATH.'DataBack/'.$filename;
		if(file_exists($filepath)){
			$sql = read_file($filepath);
			$sql = str_replace("\r\n", "\n", $sql); 
			foreach(explode(";\n", trim($sql)) as $query) {
				$rs->query(trim($query));
			}
			
			$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Data/Backin/id/'.$pre.'/fileid/'.($fileid+1).'');
			$this->success('第'.$fileid.'个备份文件恢复成功,准备恢复下一个,请稍等!');
		}else{
			$this->create_channel();
			$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Data/Showtable');
			$this->success('数据库恢复成功!');
		}
		
	}
	//下载还原
	public function backdown(){
		$filepath = RUNTIME_PATH.'DataBack/'.$_GET['id'];
		if (file_exists($filepath)) {
			$filename = $filename ? $filename : basename($filepath);
			$filetype = trim(substr(strrchr($filename, '.'), 1));
			$filesize = filesize($filepath);
			header('Cache-control: max-age=31536000');
			header('Expires: '.gmdate('D, d M Y H:i:s', time() + 31536000).' GMT');
			header('Content-Encoding: none');
			header('Content-Length: '.$filesize);
			header('Content-Disposition: attachment; filename='.$filename);
			header('Content-Type: '.$filetype);
			readfile($filepath);
			exit;
		}else{
			$this->error('出错了,没有找到分卷文件!');
		}
	}
	//删除分卷文件
	public function backdel(){
		$filename = trim($_GET['id']);
		@unlink(RUNTIME_PATH.'DataBack/'.$filename);
		$this->success($filename.'已经删除!');
	}
	//删除所有分卷文件
	public function delall(){
		foreach($_POST['ids'] as $value){
			@unlink(RUNTIME_PATH.'DataBack/'.$value);
		}
		$this->success('批量删除分卷文件成功！');
	}
	//展示高级SQL
    public function showsql(){
		$this->display('./views/admin/db_sql.html');
    }
	//执行SQL语句
    public function upsql(){
		$sql = trim($_POST['sql']);
		if (empty($sql)) {
			$this->error('SQL语句不能为空!');
		}else{
			$sql = trim(stripslashes($sql));
			$rs = new Model();
			$rs->query($sql);
			$this->success('SQL语句成功运行!');
		}
    }
	//Ajax展示字段信息
    public function ajaxfields(){
		$id = str_replace(C('db_prefix'),'',$_GET['id']);
		if (!empty($id)) {
			$rs = D("Admin.".$id);
			$array = $rs->getDbFields();
			echo "<div style='border:1px solid #ababab;width:493px;white-space:normal;word-wrap:break-word;overflow:auto;background-color:#FEFFF0;margin-top:6px;padding:3px;line-height:160%'>";
			echo "表(".C('db_prefix').$id.")含有的字段：<br>";
			foreach($array as $key=>$val){
				if(!is_int($key)){
					break;
				}
				if (ereg("cfile|username|userpwd|user|pwd",$val)){
					continue;
				}
				echo "<a href=\"javascript:rpfield('".$val."')\">".$val."</a>\r\n";
			}
			echo "</div>";
		}else{
			echo 'no fields';
		}
    }
	//执行批量替换
    public function upfields(){
		if(empty($_POST['rpfield'])){
			$this->error("请手工指定要替换的字段!");
		}
		if(empty($_POST['rpstring'])){
			$this->error("请指定要被替换内容！");
		}
		$exptable = str_replace(C('db_prefix'),'',$_POST['exptable']);
		$rs = D("Admin.".$exptable);
		$exptable  = C('db_prefix').$exptable;//表
		$rpfield   = trim($_POST['rpfield']);//字段
		$rpstring  = $_POST['rpstring'];//被替换的
		$tostring  = $_POST['tostring'];//替换内容
		$condition = trim(stripslashes($_POST['condition']));//条件
		$condition = empty($condition) ? '' : " where $condition ";
		$rs->execute(" update $exptable set $rpfield=Replace($rpfield,'$rpstring','$tostring') $condition ");
		$lastsql = $rs->getLastSql();
		$this->success('批量替换完成!SQL执行语句'.$lastsql);
    }										
}
?>