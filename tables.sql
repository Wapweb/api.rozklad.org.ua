--
-- База данных: `rozklad`
--

-- --------------------------------------------------------

--
-- Структура таблицы `group`
--

CREATE TABLE IF NOT EXISTS `group` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_full_name` varchar(50) NOT NULL,
  `group_prefix` varchar(100) NOT NULL,
  `group_okr` enum('bachelor','specialist','magister') NOT NULL,
  `group_type` enum('extramural','daily') NOT NULL,
  `group_url` varchar(1000) NOT NULL DEFAULT '',
  `group_anomaly` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`group_id`),
  KEY `group_full_name` (`group_full_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1784 ;

-- --------------------------------------------------------

--
-- Структура таблицы `lesson`
--

CREATE TABLE IF NOT EXISTS `lesson` (
  `lesson_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(5) NOT NULL,
  `day_number` enum('1','2','3','4','5','6','7') NOT NULL,
  `day_name` varchar(50) NOT NULL,
  `lesson_number` int(1) NOT NULL DEFAULT '1',
  `lesson_name` varchar(1000) NOT NULL DEFAULT '',
  `lesson_room` varchar(100) NOT NULL DEFAULT '',
  `lesson_type` varchar(50) NOT NULL DEFAULT '',
  `teacher_name` varchar(255) NOT NULL DEFAULT '',
  `lesson_week` enum('1','2') NOT NULL DEFAULT '1',
  `time_start` time NOT NULL,
  `time_end` time NOT NULL,
  `rate` enum('1','0.5','1.5') NOT NULL DEFAULT '1',
  `lesson_room_type` varchar(500) NOT NULL DEFAULT '',
  PRIMARY KEY (`lesson_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36349 ;
