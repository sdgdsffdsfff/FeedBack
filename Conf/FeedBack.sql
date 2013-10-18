--
-- Database: `feedback`
--

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE IF NOT EXISTS `feedback` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) unsigned NOT NULL COMMENT '反馈类型',
  `appid` int(10) unsigned NOT NULL COMMENT '来源APP',
  `mid` int(10) unsigned NOT NULL COMMENT '用户mid',
  `uuid` varchar(100) NOT NULL COMMENT '反馈来源设备ID',
  `dateline` int(10) unsigned NOT NULL COMMENT '时间',
  `contact` varchar(100) NOT NULL COMMENT '联系方式',
  `content` text NOT NULL COMMENT '反馈内容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Table structure for table `application`
--

CREATE TABLE IF NOT EXISTS `application` (
  `appid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `secret` varchar(40) NOT NULL,
  PRIMARY KEY (`appid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
