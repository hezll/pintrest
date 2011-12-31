$(document).ready(function(){
	//分类搜索
	showsearch();
	//展示地区列表
	if($("#areahtml").length>0){
		showarea();
	}	
	//展示年份列表
	if($("#yearhtml").length>0){
		showyear();
	}
	//内容页同类热门影视
	if($("#hot_video").length>0){
		$("#hot_video").load($("#hot_video").attr('href'));	
	}
	//内容页同类热门新闻
	if($("#hot_info").length>0){
		$("#hot_info").load($("#hot_info").attr('href'));	
	}
	//收起展开列表 <span id="detail_hot"></span>
	$("#plMore").click(function(){	
		$html = $(this).html();
		if($html=='展开列表'){
			$(this).html('收起列表');
			$("#pmoreContain").attr('class','play-list-right-c');
			$("#all-plist").show();
		}else{
			$(this).html('展开列表');
			$("#pmoreContain").attr('class','play-list-right');	
			$("#all-plist").hide();
		}
	});	
});
function showsearch(){
	$('#wd').focus(function(){
	if($('#wd').val()=='请输入关键字'){
			$('#wd').val('');
		}
	});
	$('#wd').blur(function(){
		if($('#wd').val()==''){
			$('#wd').val('请输入关键字');
		}
	});	
	$('#sort').hide();
	$('#cur_txt').click(function(){
		$('#sort').show();
		$('#sort>li>a').click(function(){
			$html = $(this).html();
			$action = $('#search').attr('action');
			if($html=='新闻'){
				$('#cur_txt').html('新闻');
				$('#search').attr('action',$action.replace('video/','info/'));	
			}else{
				$('#cur_txt').html('视频');
				$('#search').attr('action',$action.replace('info/','video/'));
			}
			$('#sort').hide();
		});
	});
	$('#cur_txt').blur(function(){	
		$('#sort').hide();
	});
}
//<dd id="yearhtml"></dd>
function showyear(){
	var $html='';
	var $year = $('#yearhtml').html()*1;
	for(i=2011;i>1997;i--){
		if(i == $year){
			$html +='<a href="'+SitePath+'index.php?s=video/lists/id/'+SiteCid+'/year/'+i+'" class="Year">'+i+'</a> ';
		}else{
			$html +='<a href="'+SitePath+'index.php?s=video/lists/id/'+SiteCid+'/year/'+i+'">'+i+'</a> ';
		}
	}
	$('#yearhtml').html($html);
}
//<dd id="areahtml"></dd>
function showarea(){
	var $html='';
	var $area=$('#areahtml').html();
	var array_str = ['中国','内地','香港','台湾','韩国','日本','美国','英国','法国','意大利','德国','西班牙','泰国'];
	for (var key in array_str){
		if($area == array_str[key]){
			$html +='<a href="'+SitePath+'index.php?s=video/lists/id/'+SiteCid+'/area/'+encodeURIComponent(array_str[key])+'" class="Area">'+array_str[key]+'</a> ';
		}else{
			$html +='<a href="'+SitePath+'index.php?s=video/lists/id/'+SiteCid+'/area/'+encodeURIComponent(array_str[key])+'">'+array_str[key]+'</a> ';
		}
	}
	$('#areahtml').html($html);
}