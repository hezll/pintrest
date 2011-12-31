SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


CREATE TABLE IF NOT EXISTS gx_adsense (
  id smallint(5) unsigned NOT NULL auto_increment,
  title varchar(50) NOT NULL,
  content text NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `gx_adsense` (`id`, `title`, `content`) VALUES
(1, 'index-96090', '首页通栏广告1'),
(2, 'index-96090-2', '首页通栏广告2'),
(3, 'right-250250', '内容页右侧广告 宽250 高250'),
(4, 'play-left', '播放页左侧广告 宽160 高480'),
(5, 'play-right', '播放页右侧广告 宽160 高480');


CREATE TABLE IF NOT EXISTS gx_channel (
  id smallint(5) unsigned NOT NULL auto_increment,
  pid smallint(5) NOT NULL,
  oid smallint(5) NOT NULL,
  mid tinyint(2) NOT NULL,
  status tinyint(1) NOT NULL default '1',
  cname char(20) NOT NULL,
  cfile varchar(20) NOT NULL,
  ctpl char(20) NOT NULL,
  cwebsite varchar(255) NOT NULL,
  ctitle varchar(50) NOT NULL,
  ckeywords varchar(255) NOT NULL,
  cdescription varchar(255) NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `gx_channel` (`id`, `pid`, `oid`, `mid`, `status`, `cname`, `cfile`, `ctpl`, `cwebsite`, `ctitle`, `ckeywords`, `cdescription`) VALUES
(1, 0, 1, 1, 1, '电影', 'dianying', 'video_channel', 'http://', '', '', ''),
(2, 0, 2, 1, 1, '电视剧', 'dianshiju', 'video_channel', 'http://', '', '', ''),
(3, 0, 3, 1, 1, '动漫', 'dongman', 'video_list', 'http://', '', '', ''),
(4, 0, 4, 1, 1, '综艺', 'zongyi', 'video_channel', 'http://', '', '', ''),
(5, 0, 5, 1, 1, '体育', 'tiyu', 'video_list', 'http://', '', '', ''),
(6, 0, 6, 1, 1, '纪录片', 'jilupian', 'video_list', 'http://', '', '', ''),
(7, 0, 7, 2, 1, '资讯', 'zixun', 'info_channel', '1', '1', '1', ''),
(8, 1, 8, 1, 1, '动作片', 'dongzuopian', 'video_list', 'http://', '', '', ''),
(9, 1, 9, 1, 1, '喜剧片', 'xijupian', 'video_list', 'http://', '', '', ''),
(10, 1, 10, 1, 1, '爱情片', 'aiqingpian', 'video_list', 'http://', '', '', ''),
(11, 1, 11, 1, 1, '科幻片', 'kehuanpian', 'video_list', 'http://', '', '', ''),
(12, 1, 12, 1, 1, '剧情片', 'juqingpian', 'video_list', 'http://', '', '', ''),
(13, 1, 13, 1, 1, '恐怖片', 'kongbupian', 'video_list', 'http://', '', '', ''),
(14, 1, 14, 1, 1, '战争片', 'zhanzhengpian', 'video_list', 'http://', '', '', ''),
(15, 2, 15, 1, 1, '国产剧', 'guochanju', 'video_list', 'http://', '', '', ''),
(16, 2, 16, 1, 1, '台湾剧', 'taiwanju', 'video_list', 'http://', '', '', ''),
(17, 2, 17, 1, 1, '香港剧', 'xianggangju', 'video_list', 'http://', '', '', ''),
(18, 2, 18, 1, 1, '韩国剧', 'hanguoju', 'video_list', 'http://', '', '', ''),
(19, 2, 19, 1, 1, '日本剧', 'ribenju', 'video_list', 'http://', '', '', ''),
(20, 2, 20, 1, 1, '欧美剧', 'oumeiju', 'video_list', 'http://', '', '', ''),
(21, 2, 21, 1, 1, '海外剧', 'haiwaiju', 'video_list', 'http://', '', '', '');



CREATE TABLE IF NOT EXISTS gx_comment (
  id mediumint(8) unsigned NOT NULL auto_increment,
  did mediumint(8) NOT NULL,
  mid tinyint(2) NOT NULL,
  uid mediumint(8) NOT NULL,
  content varchar(255) NOT NULL,
  up mediumint(8) NOT NULL default '0',
  down mediumint(8) NOT NULL default '0',
  ip varchar(20) NOT NULL,
  addtime int(11) NOT NULL,
  `status` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY did (did),
  KEY uid (uid),
  KEY addtime (addtime)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS gx_gbook (
  id mediumint(8) unsigned NOT NULL auto_increment,
  errid mediumint(8) NOT NULL default '0',
  uid mediumint(8) NOT NULL,
  content varchar(255) NOT NULL,
  recontent text NOT NULL,
  ip varchar(20) NOT NULL,
  top tinyint(1) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  addtime int(11) NOT NULL,
  PRIMARY KEY  (id),
  KEY addtime (addtime),
  KEY uid (uid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS gx_info (
  id mediumint(8) unsigned NOT NULL auto_increment,
  cid smallint(5) NOT NULL,
  title varchar(255) NOT NULL,
  keywords varchar(255) NOT NULL,
  color char(8) NOT NULL,
  picurl varchar(255) NOT NULL,
  inputer varchar(50) NOT NULL,
  reurl varchar(255) NOT NULL,
  remark text NOT NULL,
  content text NOT NULL,
  hits mediumint(8) NOT NULL,
  monthhits int(8) NOT NULL,
  weekhits int(8) NOT NULL,
  dayhits int(8) NOT NULL,
  hitstime int(11) NOT NULL,
  stars tinyint(1) NOT NULL,
  status tinyint(1) NOT NULL,
  up mediumint(8) NOT NULL,
  down mediumint(8) NOT NULL,
  jumpurl varchar(255) NOT NULL,
  letter char(2) NOT NULL,
  addtime int(11) NOT NULL,
  score decimal(3,1) NOT NULL,
  scoreer smallint(6) NOT NULL,
  PRIMARY KEY  (id),
  KEY cid (cid),
  KEY addtime (addtime,cid),
  KEY hits (hits,cid),
  KEY up (up),
  KEY down (down),
  KEY score (score)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS gx_link (
  id smallint(5) unsigned NOT NULL auto_increment,
  title varchar(50) NOT NULL,
  logo varchar(255) NOT NULL,
  url varchar(255) NOT NULL,
  oid tinyint(3) NOT NULL,
  `type` tinyint(1) NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `gx_link` (`id`, `title`, `logo`, `url`, `oid`, `type`) VALUES
(1, '光线cms', 'http://', 'http://www.gxcms.com', 1, 1);




CREATE TABLE IF NOT EXISTS gx_master (
  id tinyint(3) unsigned NOT NULL auto_increment,
  `user` varchar(50) NOT NULL,
  pwd char(32) NOT NULL,
  usertype varchar(100) NOT NULL,
  logincount smallint(5) NOT NULL,
  loginip varchar(40) NOT NULL,
  logintime int(11) NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO gx_master (id, user, pwd, usertype, logincount, loginip, logintime) VALUES
(1, 'admin', '7fef6171469e80d32c0559f88b377245', '1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1', 1, '127.0.0.1', 1287921825);



CREATE TABLE IF NOT EXISTS gx_slide (
  id tinyint(2) unsigned NOT NULL auto_increment,
  oid tinyint(2) NOT NULL,
  title varchar(50) NOT NULL,
  picurl varchar(100) NOT NULL,
  link varchar(100) NOT NULL,
  content varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS gx_special (
  id mediumint(8) unsigned NOT NULL auto_increment,
  banner varchar(150) NOT NULL,
  title varchar(150) NOT NULL,
  keywords varchar(150) NOT NULL,
  tpl varchar(50) NOT NULL,
  logo varchar(150) NOT NULL,
  content text NOT NULL,
  aids text NOT NULL COMMENT '专题文章',
  mids text NOT NULL COMMENT '专题影片',
  addtime int(11) NOT NULL,
  hits mediumint(8) NOT NULL,
  monthhits int(8) NOT NULL,
  weekhits int(8) NOT NULL,
  dayhits int(8) NOT NULL,
  hitstime int(11) NOT NULL,
  `status` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS gx_user (
  id mediumint(8) unsigned NOT NULL auto_increment,
  username varchar(50) NOT NULL,
  userpwd char(32) NOT NULL,
  money mediumint(9) NOT NULL,
  `status` tinyint(1) NOT NULL default '1',
  pay tinyint(1) NOT NULL,
  question varchar(50) NOT NULL,
  answer varchar(50) NOT NULL,
  logip varchar(16) NOT NULL,
  lognum smallint(5) NOT NULL default '0',
  logtime int(10) NOT NULL,
  joinip varchar(16) NOT NULL,
  jointime int(10) NOT NULL,
  duetime int(10) NOT NULL,
  qq varchar(12) NOT NULL,
  email varchar(50) NOT NULL,
  face varchar(50) NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `gx_user` (`id`, `username`, `userpwd`, `money`, `status`, `pay`, `question`, `answer`, `logip`, `lognum`, `logtime`, `joinip`, `jointime`, `duetime`, `qq`, `email`, `face`) VALUES
(1, '游客', '74b87337454200d4d33f80c4663dc5e5', 120, 1, 0, '123456', '123456', '127.0.0.1', 21, 1299233747, '127.0.0.1', 1298433430, 1300852630, '', '123@qq.com', '');


CREATE TABLE IF NOT EXISTS gx_userview (
  id mediumint(8) unsigned NOT NULL auto_increment,
  did mediumint(8) NOT NULL,
  uid mediumint(8) NOT NULL,
  viewtime int(10) NOT NULL,
  PRIMARY KEY  (id),
  KEY id (id),
  KEY viewtime (viewtime),
  KEY did (did),
  KEY uid (uid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS gx_video (
  id mediumint(8) unsigned NOT NULL auto_increment,
  cid smallint(5) NOT NULL,
  title varchar(255) NOT NULL,
  intro varchar(255) NOT NULL,
  keywords varchar(255) NOT NULL,
  color char(8) NOT NULL,
  actor varchar(255) NOT NULL,
  director varchar(255) NOT NULL,
  content text NOT NULL,
  picurl varchar(255) NOT NULL,
  area char(10) NOT NULL,
  `language` char(10) NOT NULL,
  `year` smallint(4) NOT NULL,
  `serial` varchar(50) NOT NULL default '0',
  addtime int(11) NOT NULL,
  hits mediumint(8) NOT NULL default '0',
  monthhits int(8) NOT NULL,
  weekhits int(8) NOT NULL,
  dayhits int(8) NOT NULL,
  hitstime int(11) NOT NULL,
  stars tinyint(1) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '1',
  up mediumint(8) NOT NULL default '0',
  down mediumint(8) NOT NULL default '0',
  playurl longtext NOT NULL,
  inputer varchar(30) NOT NULL,
  reurl varchar(255) NOT NULL,
  letter char(2) NOT NULL,
  score decimal(3,1) NOT NULL,
  scoreer smallint(6) NOT NULL,
  PRIMARY KEY  (id),
  KEY cid (cid),
  KEY addtime (addtime,cid),
  KEY hits (hits,cid),
  KEY up (up),
  KEY down (down),
  KEY score (score)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `gx_co_urls` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `md5` varchar(32) NOT NULL default '',
  `nid` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `md5` (`md5`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;


CREATE TABLE IF NOT EXISTS `gx_co_node` (
  `id` smallint(6) unsigned NOT NULL auto_increment,
  `name` varchar(20) NOT NULL,
  `lastdate` int(10) unsigned NOT NULL default '0',
  `menutype` tinyint(3) unsigned default '2',
  `cid` smallint(5) unsigned default '0',
  `colmode` varchar(50) default NULL,
  `direct` tinyint(1) NOT NULL default '0',
  `sourcecharset` varchar(8) NOT NULL,
  `sourcetype` tinyint(1) unsigned NOT NULL default '0',
  `urlpage` text NOT NULL,
  `pagesize_start` tinyint(3) unsigned NOT NULL default '0',
  `pagesize_end` mediumint(8) unsigned NOT NULL default '0',
  `par_num` tinyint(3) unsigned NOT NULL default '1',
  `url_contain` char(100) NOT NULL,
  `url_except` char(100) NOT NULL,
  `picmode` tinyint(3) NOT NULL default '2',
  `pic_start` char(100) NOT NULL default '',
  `pic_end` char(100) NOT NULL default '',
  `url_start` char(100) NOT NULL default '',
  `url_end` char(100) NOT NULL default '',
  `fields` varchar(255) default NULL,
  `title_rule` char(100) NOT NULL,
  `title_filter` text NOT NULL,
  `cname_rule` char(100) NOT NULL default '',
  `cname_filter` text NOT NULL,
  `intro_rule` char(100) NOT NULL default '',
  `intro_filter` text NOT NULL,
  `time_rule` char(100) NOT NULL default '',
  `time_filter` text NOT NULL,
  `director_rule` char(100) NOT NULL default '',
  `director_filter` text NOT NULL,
  `actor_rule` char(100) NOT NULL default '',
  `actor_filter` text NOT NULL,
  `content_rule` char(100) NOT NULL,
  `content_filter` text NOT NULL,
  `picurl_rule` char(100) NOT NULL default '',
  `picurl_filter` text NOT NULL,
  `range` tinyint(1) unsigned NOT NULL default '0',
  `playmode` tinyint(1) unsigned NOT NULL default '3',
  `playlist_start` char(100) NOT NULL default '',
  `playlist_end` char(100) NOT NULL default '',
  `purl_range` tinyint(1) unsigned NOT NULL default '2',
  `playurl_start` char(100) NOT NULL default '',
  `playurl_end` char(100) NOT NULL default '',
  `playlink_rule` char(100) NOT NULL default '',
  `playlink_filter` text NOT NULL,
  `playurl_rule` char(100) NOT NULL default '',
  `playurl_filter` text NOT NULL,
  `area_rule` char(100) NOT NULL default '',
  `area_filter` text NOT NULL,
  `language_rule` char(100) NOT NULL default '',
  `language_filter` text NOT NULL,
  `year_rule` char(100) NOT NULL default '',
  `year_filter` text NOT NULL,
  `serial_rule` char(100) NOT NULL default '',
  `serial_filter` text NOT NULL,
  `vname_rule` char(100) NOT NULL default '',
  `vname_filter` text NOT NULL,
  `vnamemode` tinyint(3) NOT NULL default '2',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ;
 


CREATE TABLE IF NOT EXISTS `gx_co_content` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `nid` int(10) unsigned NOT NULL default '0',
  `status` tinyint(1) unsigned NOT NULL default '0',
  `url` char(255) NOT NULL,
  `title` char(100) NOT NULL,
  `data` text NOT NULL,
  `addtime` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `status` (`status`),
  KEY `nid` (`nid`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ;


CREATE TABLE IF NOT EXISTS `gx_co_channel` (
  `id` int(11) NOT NULL auto_increment,
  `cname` varchar(20) NOT NULL default '',
  `reid` smallint(6) NOT NULL default '0',
  `nid` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `gx_co_channel` (`id`, `cname`, `reid`, `nid`) VALUES (1,'动画卡通',3,0);
