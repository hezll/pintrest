/*
This file is used specifically for video history
*/
	
	var hoverBackgroundColor = "#ffffff";
	var hoverTextColor = "#025196";
	
	function VSetCookie(name,value)
	{
		var Days = 30; //cookie 将被保存 30 天
		var exp  = new Date();   //new Date("December 31, 9998");
		exp.setTime(exp.getTime() + Days*24*60*60*1000);
		document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
	}
	function VgetCookie(name)//取cookies函数        
	{
		var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
		if(arr != null) return unescape(arr[2]); return null;
	}
	
	
	/*=============================================*/				
	function findPos(obj) 
	{
	  var curleft = curtop = 0;
	  if (obj.offsetParent) {
		  curleft = obj.offsetLeft;
		  curtop = obj.offsetTop+26;
		  while (obj = obj.offsetParent) {
			  curleft += obj.offsetLeft;
			  curtop += obj.offsetTop;
		  }
	  }
	  return [curleft,curtop];
	}
	
	function fnDisplayMenu(parent, mnuName)
	{
	  var mnuElem = document.getElementById(mnuName);
	  mnuElem.style.display = "block";
	  //mnuElem.style.top = fnGetMenuTopPosition();
 
		  if (mnuName == "mnuArtStyles" )
	  {
		  var placement = findPos(parent);
 		 mnuElem.style.left = placement[0] + "px";
		 mnuElem.style.top  = placement[1]+ "px";

	  }
	  fnHighlightTD(mnuName);
	
	}
	function fnDisplayMenu2(parent, mnuName)
	{
	  var mnuElem = document.getElementById(mnuName);
	      mnuElem.style.display = "block";
	  if (mnuName == "mnuArtStyles" )
	  {
		  var placement = findPos(parent);
		  mnuElem.style.left = placement[0] + "px";
		  mnuElem.style.top  = placement[1] + "px";

	  }
	  fnHighlightTD(mnuName);
	}
	
	function fnHideMenu(mnuName)
	{
	  var mnuElem = document.getElementById(mnuName);
	  mnuElem.style.display = "none";
	}
	
	function fnHighlightTD(mnuName)
	{
	  
	  var elem = document.getElementById(mnuName + "Link");
	  
	  elem.style.backgroundColor = hoverBackgroundColor;
	  elem.style.color = hoverTextColor;
	}
	
	function fnRemoveHighlight(mnuName)
	{
	  var elem = document.getElementById(mnuName + "Link");
	  elem.style.backgroundColor ='';// hoverBackgroundColor;
	  elem.style.color = "#025196"; //hoverBackgroundColor;
	}

	
	function getCookie(name)//取cookies函数        
	{
		var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
		 if(arr != null) return unescape(arr[2]); return null;
	
	}
	function delCookie(name)//删除cookie
	{
		var exp = new Date();
		exp.setTime(exp.getTime() - 1);
		var cval=getCookie(name);
		if(cval!=null) document.cookie= name + "="+cval+";expires="+exp.toGMTString();
		alert('历史记录已经清空，请刷新页面！');
	}

   function CheckAdd(name,ckname,url,num){
        var result=VgetCookie(ckname);
        var str;
        if(result==null){
          result="";
        }
       if(num!=''){
          name=name+' '+num;
        }
        str=name+"ddd"+url+"ttt";
        if(result.indexOf(name)>=0){ //删除同片历史记录
              result= result.replace(str,"");
        }
          result=str+result;
          VSetCookie(ckname,result);
   }

  
	$(document).ready(function(){
		if(document.getElementById("history")){					   
		var result=getCookie("gxhis");
		if(result==null)
		{
			document.getElementById("history").innerHTML='<div class="d5" style="text-align:right;"><a href="javascript:void(0);">暂无观看记录</a></div>';
		}
		else
		{
			var bb="delCookie('gxhis')";
			var cc='<div class="d5" style="text-align:right;"><a href="javascript:void(0);" onclick="'+bb+'">清空观看记录</a></div>';
			var arr=result.split("ttt");
			if(arr.length>6)
			{
				for(var i=5;i>-1;i--)
				{
					var act=arr[i].split('ddd');
					if(act[0]!="")
					{
						cc='<div class="list"><a href="'+act[1]+'" title="'+act[0]+'" class="title">'+act[0].substr(0,10)+'</a><a href="'+act[1]+'"  style="color:#4E8000;">继续观看</a></div>'+cc;
					}
				}
			}
			else
			{
				for(var i=arr.length-1;i>-1;i--)
				{
					var act=arr[i].split('ddd');
					if(act[0]!="")
					{
						cc='<div class="list"><a href="'+act[1]+'" title="'+act[0]+'"  class="title">'+act[0].substr(0,10)+'</a><a href="'+act[1]+'"  style="color:#4E8000;">继续观看</a></div>'+cc;
					}
				}
			}
			document.getElementById("history").innerHTML=cc;
		}
		}
		
		});
