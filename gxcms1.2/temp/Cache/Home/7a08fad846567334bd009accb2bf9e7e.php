<?php if (!defined('THINK_PATH')) exit();?><?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0">
<channel>
<title><?php echo ($webname); ?></title> 
<description><?php echo ($webname); ?></description> 
<link><?php echo ($weburl); ?></link> 
<language>zh-cn</language> 
<docs><?php echo ($webname); ?></docs> 
<generator>Rss Powered By <?php echo ($weburl); ?></generator> 
<image>
<url><?php echo ($weburl); ?>public/images/logo.gif</url> 
</image>
<?php if(is_array($listmap)): $i = 0; $__LIST__ = $listmap;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$video): ++$i;$mod = ($i % 2 )?><item>
<title><?php echo (htmlspecialchars($video["title"])); ?><?php echo (htmlspecialchars($video["intro"])); ?></title> 
<link><?php echo get_base_url($weburl,$video['readurl']);?></link>
<author><?php echo (htmlspecialchars($video["actor"])); ?></author>
<pubDate><?php echo (date('Y-m-d H:i:s',$video["addtime"])); ?></pubDate>
<description><![CDATA["<?php echo (get_replace_html($video["content"],0,200)); ?>"]]></description> 
</item><?php endforeach; endif; else: echo "" ;endif; ?>
</channel>
</rss>