//全反选
$("#checkall").click(function(){
	$("input[name='ids[]']").each(function(){
		if(this.checked == false)
			this.checked = true;
		else
		   this.checked = false;
	});
})
//分页跳转
function pagego($url,$total){
	$page = $('#page').val();
	if ($page>0 && ($page<=$total)){
		$url = $url.replace('{!page!}',$page);
		location.href = $url;
	}
	return false;
}