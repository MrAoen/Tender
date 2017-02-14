<?php
/*
*/

$caller->host = 'localhost';
$caller->user = 'retalkz';
$caller->pass = '89nb8Jc3Ti';
$caller->name = 'retalkz_tender';

$caller->prefix = 'dp';
$caller->queries = array(
    /* all { */
    '1' => "SELECT * FROM `%prefix%admins` WHERE `login` = '%login%' AND `password` = '%pw%' LIMIT 1",
    '2' => "UPDATE `%prefix%admins` SET `lastin` = '%lastin%', `lastip` = '%lastip%' WHERE `id` = '%id%'",
    '3' => "UPDATE `%prefix%admins` SET `params` = '%params%' WHERE `id` = '%id%'",
    '4' => "SET NAMES cp1251",
    '5' => "DELETE FROM `%prefix%temp_files` WHERE `id` = '%id%'",
    '6' => "DELETE FROM `%prefix%temp_files` WHERE `id` IN (%ids%)",
    '7' => "SELECT `temp_orig_filename`, `temp_filename`, `temp_extension`, `future_filename` FROM `%prefix%temp_files` WHERE `id` = '%id%' LIMIT 1",
    '8' => "SELECT `id` FROM `%prefix%temp_files` WHERE `future_filename` = '%filename%' AND `future_location` = '%location%' AND `id` != '%id%'",
    '9' => "INSERT INTO `%prefix%temp_files` (`datead`, `temp_orig_filename`, `temp_filename`, `temp_extension`, `future_location`, `future_filename`) VALUES ('%datead%', '%temp_orig_filename%', '%temp_filename%', '%temp_extension%', '%future_location%', '%future_filename%')",
    '10' => "UPDATE `%prefix%temp_files` SET `datead` = '%datead%', `temp_orig_filename` = '%temp_orig_filename%', `temp_filename` = '%temp_filename%', `temp_extension` = '%temp_extension%', `future_location` = '%future_location%', `future_filename` = '%future_filename%' WHERE `id` = '%id%'",
    '11' => "SELECT %params% FROM `%prefix%%table%` WHERE `id` = '%id%' LIMIT 1",
    '12' => "SELECT FOUND_ROWS()",
    '13' => "SELECT `value` FROM `%prefix%params` WHERE `param` = '%param%'",
    '14' => "REPLACE INTO `%prefix%params` (`param`, `value`) VALUES ('%param%', '%value%')",
    /* move_tree { */
    '100' => "UPDATE `%prefix%%table%` SET `%ord%` = `%ord%` - 1 WHERE `%parent%` = '%parentid%' AND `%ord%` > '%v1%' AND `%ord%` < '%v2%'",
    '101' => "UPDATE `%prefix%%table%` SET `%ord%` = `%ord%` + 1 WHERE `%parent%` = '%parentid%' AND `%ord%` > '%v1%' AND `%ord%` < '%v2%'",
    '102' => "UPDATE `%prefix%%table%` SET `%ord%` = `%ord%` - 1 WHERE `%ord%` > '%v1%' AND `%ord%` < '%v2%'",
    '103' => "UPDATE `%prefix%%table%` SET `%ord%` = `%ord%` + 1 WHERE `%ord%` > '%v1%' AND `%ord%` < '%v2%'",
    '104' => "UPDATE `%prefix%%table%` SET `%ord%` = '%ord_new%' WHERE `id` = '%id%'",
    '105' => "UPDATE `%prefix%%table%` SET `%ord%` = `%ord%` - 1 WHERE `%parent%` = '%parentid%' AND `%ord%` > '%ord_value%'",
    '106' => "UPDATE `%prefix%%table%` SET `%ord%` = `%ord%` + 1 WHERE `%parent%` = '%parentid%' AND `%ord%` > '%ord_value%'",
    '107' => "UPDATE `%prefix%%table%` SET `%ord%` = '%ord_new%', `%parent%` = '%parentid%' WHERE `id` = '%id%'",
    '108' => "SELECT `%parent%` AS `parentid` FROM `%prefix%%table%` WHERE `id` = '%parentid%' LIMIT 1",
    '109' => "UPDATE `%prefix%%table%` SET `%ord%` = `%ord%` + 1 WHERE `%parent%` = '%parentid%' AND `%ord%` > '%ord_value%'",
    '110' => "UPDATE `%prefix%%table%` SET `%ord%` = `%ord%` + 1 WHERE `%ord%` > '%ord_value%'",
    '111' => "SELECT MAX(`%ord%`) FROM `%prefix%%table%` WHERE `%parent%` = '%parentid%' AND `id` != '%id%'",
    '112' => "SELECT MAX(`%ord%`) FROM `%prefix%%table%` WHERE `id` != '%id%'",
    '113' => "SELECT MAX(`%ord%`) FROM `%prefix%%table%` WHERE `%parent%` = '%parentid%'",
    '114' => "SELECT MAX(`%ord%`) FROM `%prefix%%table%`",
    '115' => "UPDATE `%prefix%%table%` SET `%ord%` = `%ord%` - 1 WHERE `%parent%` = '%parentid%' AND `%ord%` > '%ord_value%'",
    '116' => "UPDATE `%prefix%%table%` SET `%ord%` = `%ord%` - 1 WHERE `%ord%` > '%ord_value%'",
    /* move_tree } */
    /* all } */

    /* files { */
    '500' => "SELECT `temp_filename` FROM `%prefix%temp_files` WHERE `future_filename` = '%filename%' AND `future_location` = '%location%' LIMIT 1",
    /* files } */

    /* admins { */
    '701' => "SELECT `id`, `fname`, `sname`, `lname`, `lastin`, `emails`, `typeid` FROM `%prefix%admins` ORDER BY `typeid`, `fname`, `sname`, `lname`, `id`",
    '702' => "SELECT * FROM `%prefix%admins` WHERE `id` = '%id%' LIMIT 1",
    '703' => "UPDATE `%prefix%admins` SET `permissions` = '%perms%' WHERE `id` = '%id%'",
    '704' => "UPDATE `%prefix%%t%` SET `lastchangeadminid` = 0 WHERE `lastchangeadminid` = '%id%'",
    /* admins } */

    /* templates { 1100..1299 } */

    /* params { */
    '1300' => "SELECT * FROM `%prefix%params` WHERE `id` = '%id%' LIMIT 1",
    '1301' => "SELECT `id`, `chapterid`, `srvnotes`, `paramvalue` FROM `%prefix%params` ORDER BY `chapterid`, `srvnotes`",
    /* params } */

    /* texts { */
    '1500' => "SELECT * FROM `%prefix%texts` WHERE `id` = '%id%' LIMIT 1",
    '1501' => "SELECT `id`, `chapterid`, `text_%lang%` AS `value`, `srvnotes` FROM `%prefix%texts` ORDER BY `chapterid`, `srvnotes`",
    /* params } */

    /* menu_top { */
    '1700' => "SELECT * FROM `%prefix%menu_top` WHERE `id` = '%id%' LIMIT 1",
    '1701' => "SELECT MAX(`ord`) FROM `%prefix%menu_top`",
    '1702' => "SELECT `ord` + 1 AS `ord_next`, `title_%lang%` AS `title` FROM `%prefix%menu_top` WHERE `id` != '%id%' ORDER BY `ord`",
    '1703' => "SELECT `id`, `ord`, `title_%lang%` AS `title` FROM `%prefix%menu_top` ORDER BY `ord`",
    '1704' => "SELECT * FROM `%prefix%menu_top` ORDER BY `ord`",
    /* menu_top } */

    /* index { */
    '1900' => "SELECT `addts`, `url`, `ip`, `last_in` FROM `%prefix%log` WHERE `addts` > '%tsmin%'",
    '1901' => "SELECT `id`, `lastchangets`, `value` FROM `%prefix%cache` WHERE `typeid` = 1 LIMIT 1",
    '1902' => "UPDATE `%prefix%cache` SET `lastchangets` = '%lastchangets%', `value` = '%value%' WHERE `id` = '%id%'",
    /* index } */

    /* offices { */
    '2100' => "SELECT * FROM `%prefix%offices` WHERE `id` = '%id%' LIMIT 1",
    '2101' => "SELECT MAX(`ord`) FROM `%prefix%offices` WHERE `regionid` = '%rid%'",
    '2102' => "SELECT `ord` + 1 AS `ord_next`, `address_%lang%` AS `address` FROM `%prefix%offices` WHERE `id` != '%id%' AND `regionid` = '%rid%' ORDER BY `ord`",
    '2103' => "SELECT `%prefix%offices`.`id` AS `id`, `%prefix%offices`.`ord` AS `ord`, `%prefix%offices`.`address_%lang%` AS `address`, `%prefix%regions`.`id` AS `regionid`, `%prefix%regions`.`title_%lang%` AS `regiontitle` FROM `%prefix%offices`, `%prefix%regions` WHERE `%prefix%offices`.`regionid` = `%prefix%regions`.`id` GROUP BY `%prefix%offices`.`id` ORDER BY `%prefix%regions`.`title_%lang%`, `%prefix%offices`.`ord`",
    '2104' => "SELECT `id`, `title_%lang%` AS `title` FROM `%prefix%regions` ORDER BY `title_%lang%`",
    '2105' => "SELECT `regionid`, `ord` + 1 AS `ord_next`, `address_%lang%` AS `address` FROM `%prefix%offices` WHERE `id` != '%id%' ORDER BY `regionid`, `ord`",
    /* offices } */

    /* regions { */
    '2300' => "SELECT * FROM `%prefix%regions` WHERE `id` = '%id%' LIMIT 1",
    '2301' => "SELECT `id`, `title_%lang%` AS `title` FROM `%prefix%regions` ORDER BY `title_%lang%`",
    '2302' => "DELETE FROM `%prefix%offices` WHERE `regionid` = '%rid%'",
    /* regions } */

    /* news { */
    '2500' => "SELECT * FROM `%prefix%news` WHERE `id` = '%id%' LIMIT 1",
    '2501' => "DELETE FROM `%prefix%news_chapters` WHERE `newsid` = '%nid%'",
    '2502' => "SELECT `id`, `title_%lang%` AS `title` FROM `%prefix%chapters_news` ORDER BY `ord`",
    '2503' => "DELETE FROM `%prefix%news_chapters` WHERE `newsid` = '%nid%'",
    '2504' => "INSERT INTO `%prefix%news_chapters` (`newsid`, `chapterid`) VALUES ('%nid%', '%cid%')",
    '2505' => "SELECT SQL_CALC_FOUND_ROWS `id`, `id`, `datets`, `datepub`, `title_%lang%` AS `title` FROM `%prefix%news` ORDER BY `datets` DESC LIMIT %lim%",
    '2506' => "SELECT SQL_CALC_FOUND_ROWS `id`, `id`, `datets`, `datepub`, `brief_%lang%` AS `title`, `img_s` AS `img` FROM `%prefix%news` ORDER BY `datets` DESC LIMIT %lim%",
    '2507' => "SELECT `chapterid` FROM `%prefix%news_chapters` WHERE `newsid` = '%nid%'",
    '2508' => "DELETE FROM `%prefix%comments` WHERE `objid` = '%oid%' AND `objtype` = 1",
    /* news } */

    /* chapters_news { */
    '2800' => "SELECT * FROM `%prefix%chapters_news` WHERE `id` = '%id%' LIMIT 1",
    '2801' => "SELECT MAX(`ord`) FROM `%prefix%chapters_news`",
    '2802' => "SELECT `ord` + 1 AS `ord_next`, `title_%lang%` AS `title` FROM `%prefix%chapters_news` WHERE `id` != '%id%' ORDER BY `ord`",
    '2803' => "SELECT `id`, `ord`, `title_%lang%` AS `title` FROM `%prefix%chapters_news` ORDER BY `ord`",
    '2804' => "DELETE FROM `%prefix%news_chapters` WHERE `chapterid` = '%cid%'",
    /* chapters_news } */

    /* users { */
    '3400' => "SELECT * FROM `%prefix%users` WHERE `id` = '%id%' LIMIT 1",
    '3401' => "UPDATE `%prefix%users` SET `status` = '%status%' WHERE `id` = '%id%'",
    '3402' => "SELECT `id`, `title_%lang%` AS `title` FROM `%prefix%chapters_users` ORDER BY `ord`",
    '3403' => "DELETE FROM `%prefix%user_chapters` WHERE `userid` = '%aid%'",
    '3404' => "INSERT INTO `%prefix%user_chapters` (`userid`, `chapterid`) VALUES ('%aid%', '%cid%')",
    '3405' => "SELECT SQL_CALC_FOUND_ROWS %fields% FROM `%prefix%users` ORDER BY %ord% LIMIT %lim%",
    '3406' => "SELECT * FROM `%prefix%admin_user_params` LIMIT 1",
    '3407' => "SELECT `chapterid` FROM `%prefix%user_chapters` WHERE `userid` = '%aid%'",
    '3408' => "SELECT `id` FROM `%prefix%chapters_users` WHERE `url` = '%url%' LIMIT 1",
    '3409' => "SELECT * FROM `%prefix%users` ORDER BY `addts`, `id`",
    /* users } */

    /* mailings { */
    '3500' => "SELECT * FROM `%prefix%mailings` WHERE `id` = '%id%' LIMIT 1",
    '3501' => "SELECT `email` FROM `%prefix%users` WHERE `status` = '2'",
    '3502' => "SELECT `mailing_sender`, `mailing_subj`, `mailing` FROM `%prefix%templates`",
    '3503' => "INSERT INTO `%prefix%mailing_emails` (`mailingid`, `email`, `status`) VALUES ('%mailingid%', '%email%', '%status%')",
    '3504' => "UPDATE `%prefix%mailings` SET `succeed` = '%succeed%', `failed` = '%failed%' WHERE `id` = '%id%'",
    '3505' => "SELECT `email`, `status` FROM `%prefix%mailing_emails` WHERE `mailingid` = '%mid%' ORDER BY `status`, `id`",
    '3506' => "SELECT SQL_CALC_FOUND_ROWS `id`, `lastchangets`, `subj`, `succeed`, `failed` FROM `%prefix%mailings` ORDER BY `lastchangets` DESC, `id` DESC LIMIT %lim%",
    /* mailings } */

    /* params_users { */
    '3700' => "SELECT * FROM `%prefix%chapters_articles` WHERE `id` = '%id%' LIMIT 1",
    '3701' => "SELECT MAX(`ord`) FROM `%prefix%chapters_articles`",
    '3702' => "SELECT `ord` + 1 AS `ord_next`, `title_%lang%` AS `title` FROM `%prefix%chapters_articles` WHERE `id` != '%id%' ORDER BY `ord`",
    '3703' => "SELECT `id`, `ord`, `title_%lang%` AS `title` FROM `%prefix%chapters_articles` ORDER BY `ord`",
    '3704' => "DELETE FROM `%prefix%article_chapters` WHERE `chapterid` = '%cid%'",
    '3705' => "SELECT `id` FROM `%prefix%articles` WHERE `url` = '%url%' LIMIT 1",
    /* params_users } */

    /* tenders { */
    '3900' => "SELECT * FROM `%prefix%tenders` WHERE `id` = '%id%' LIMIT 1",
    '3901' => "SELECT MAX(`number`) + 1 AS `max` FROM `%prefix%tenders`",
    '3902' => "SELECT `id`, `title_%lang%` AS `title` FROM `%prefix%cities` ORDER BY `title_%lang%`",
    '3903' => "SELECT `id`, `company` FROM `%prefix%users` WHERE `status` = 3 ORDER BY `company`",
    '3904' =>
        "SELECT
				u.`company`,
				p.`datets`,
				p.`price`
			FROM `%prefix%propositions` p
			LEFT JOIN `%prefix%users` u
				ON u.`id` = p.`userid`
			WHERE p.`tenderid` = '%tid%'
			ORDER BY p.`price`",
    '3905' => "SELECT SQL_CALC_FOUND_ROWS %fields% FROM `%prefix%tenders` ORDER BY %ord% LIMIT %lim%",
    '3906' => "SELECT * FROM `%prefix%admin_tender_params` LIMIT 1",
    '3907' => "SELECT `chapterid` FROM `%prefix%tender_chapters` WHERE `tenderid` = '%aid%'",
    '3908' => "SELECT `id` FROM `%prefix%chapters_tenders` WHERE `url` = '%url%' LIMIT 1",
    '3909' =>
        "SELECT
				t.*,
				t.`body_%lang%` AS `body`,
				c.`title_%lang%` AS `city`,
				u.`company` AS `lastuser`
			FROM `%prefix%tenders` t
			LEFT JOIN `%prefix%cities` c
				ON c.`id` = t.`loadingcityid`
			LEFT JOIN `%prefix%users` u
				ON u.`id` = t.`lastuserid`
			ORDER BY t.`startts`, t.`id`",
    /* tenders } */

    /* params_tenders { */
    '4100' => "SELECT * FROM `%prefix%chapters_articles` WHERE `id` = '%id%' LIMIT 1",
    '4101' => "SELECT MAX(`ord`) FROM `%prefix%chapters_articles`",
    '4102' => "SELECT `ord` + 1 AS `ord_next`, `title_%lang%` AS `title` FROM `%prefix%chapters_articles` WHERE `id` != '%id%' ORDER BY `ord`",
    '4103' => "SELECT `id`, `ord`, `title_%lang%` AS `title` FROM `%prefix%chapters_articles` ORDER BY `ord`",
    '4104' => "DELETE FROM `%prefix%article_chapters` WHERE `chapterid` = '%cid%'",
    '4105' => "SELECT `id` FROM `%prefix%articles` WHERE `url` = '%url%' LIMIT 1",
    /* params_tenders } */

    /* cities { */
    '4300' => "SELECT * FROM `%prefix%cities` WHERE `id` = '%id%' LIMIT 1",
    '4303' => "SELECT SQL_CALC_FOUND_ROWS `id`, `title_%lang%` AS `title` FROM `%prefix%cities` ORDER BY `title_%lang%` LIMIT %lim%",
    /* cities } */

    /* commodities { */
    '5400' => "SELECT * FROM `%prefix%commodities` WHERE `id` = '%id%' LIMIT 1",
    '5405' => "SELECT `id`, `ord`, `ispublished`, `isfolder`, `title_%lang%` AS `title`, '' AS `img_l` FROM `%prefix%commodities` WHERE `parentid` = '%pid%' ORDER BY `ord`",
    '5409' => "SELECT MAX(`ord`) FROM `%prefix%commodities`",
    '5410' => "SELECT `ord` + 1 AS `ord_next`, `title_%lang%` AS `title` FROM `%prefix%commodities` WHERE `id` != '%id%' AND `parentid` = '%pid%' ORDER BY `ord`",
    '5411' => "SELECT `id`, `title_%lang%` AS `title` FROM `%prefix%commodities` WHERE `isfolder` = 1 AND `id` != '%eid%' AND `parentid` = '%pid%' ORDER BY `ord`",
    '5412' => "SELECT `id` FROM `%prefix%commodities` WHERE `isfolder` = 1 AND `parentid` = '%pid%'",
    '5413' => "DELETE FROM `%prefix%commodities` WHERE `parentid` = '%pid%'",
    '5414' => "SELECT `id` FROM `%prefix%commodities` WHERE `url` = '%url%' AND `parentid` = '%pid%' AND `id` != '%eid%' LIMIT 1",
    '5415' => "SELECT `id` FROM `%prefix%commodities` WHERE `url` = '%url%' AND `level` < '%level%' AND `id` != '%eid%' LIMIT 1",
    '5416' => "SELECT `title_%lang%` AS `title` FROM `%prefix%commodities` WHERE `id` = '%id%' LIMIT 1",
    '5417' => "SELECT `level` FROM `%prefix%commodities` WHERE `id` = '%id%' LIMIT 1",
    '5418' => "SELECT `id`, `parentid`, `isfolder`, `ord` + 1 AS `ord_next`, `title_%lang%` AS `title` FROM `%prefix%commodities` WHERE `id` != '%id%' ORDER BY `parentid`, `ord`",
    '5419' => "SELECT `id` FROM `%prefix%commodities` WHERE `isfolder` = 1 AND `parentid` = '%pid%'",
    '5420' => "UPDATE `%prefix%commodities` SET `level` = '%level%' WHERE `parentid` = '%pid%'",
    '5421' => "SELECT `parentid`, `ispublished` FROM `%prefix%commodities` WHERE `id` = '%id%' LIMIT 1",
    '5422' => "UPDATE `%prefix%commodities` SET `ispublished` = 1 WHERE `id` = '%id%'",
    '5423' => "SELECT `id` FROM `%prefix%commodities` WHERE `isfolder` = 1 AND `parentid` = '%pid%'",
    '5424' => "UPDATE `%prefix%commodities` SET `ispublished` = '%pub%' WHERE `parentid` = '%pid%'",
    '5425' => "SELECT `id`, `title_%lang%` AS `title` FROM `%prefix%clients` ORDER BY `ord`",
    '5426' => "SELECT `title_%lang%` AS `title` FROM `%prefix%clients` WHERE `id` = '%id%' LIMIT 1",
    '5427' => "SELECT `id` FROM `%prefix%commodities` WHERE `parentid` = '%pid%' AND `ispublished` = 1 LIMIT 1",
    '5428' => "UPDATE `%prefix%commodities` SET `haschilds` = '%haschilds%' WHERE `id` = '%id%'",
    '5429' => "DELETE FROM `%prefix%comments` WHERE `objid` = '%oid%' AND `objtype` = 4",
    '5430' => "DELETE FROM `%prefix%commodity_img` WHERE `ownerid` = '%id%'",
    /* commodities } */

    /* photos { */
    '6200' => "SELECT * FROM `%prefix%photos` WHERE `id` = '%id%' LIMIT 1",
    '6201' => "SELECT `img_s`, `img_l` FROM `%prefix%photos` WHERE `id` IN (%ids%)",
    '6202' => "DELETE FROM `%prefix%photos` WHERE `id` IN (%ids%)",
    '6203' => "UPDATE `%prefix%photos` SET `ispublished` = 1, `lastchangets` = '%lastchangets%', `lastchangeadminid` = '%lastchangeadminid%' WHERE `id` IN (%ids%)",
    '6204' => "UPDATE `%prefix%photos` SET `ispublished` = 0, `lastchangets` = '%lastchangets%', `lastchangeadminid` = '%lastchangeadminid%' WHERE `id` IN (%ids%)",
    '6205' => "SELECT SQL_CALC_FOUND_ROWS `id`, `id`, `ispublished`, `title`, `img_s`, `img_l` FROM `%prefix%photos` ORDER BY `lastchangets` DESC, `id` DESC LIMIT %lim%",
    '6206' => "DELETE FROM `%prefix%comments` WHERE `objid` = '%oid%' AND `objtype` = 3",
    /* photos } */

    /* comments { */
    '6400' => "SELECT * FROM `%prefix%comments` WHERE `id` = '%id%' LIMIT 1",
    '6401' => "SELECT `id`, `title_%lang%` AS `title` FROM `%prefix%news` WHERE `id` = '%id%' LIMIT 1",
    '6402' => "DELETE FROM `%prefix%comments` WHERE `id` IN (%ids%)",
    '6403' => "UPDATE `%prefix%comments` SET `ispublished` = 1, `lastchangets` = '%lastchangets%', `lastchangeadminid` = '%lastchangeadminid%' WHERE `id` IN (%ids%)",
    '6404' => "UPDATE `%prefix%comments` SET `ispublished` = 0, `lastchangets` = '%lastchangets%', `lastchangeadminid` = '%lastchangeadminid%' WHERE `id` IN (%ids%)",
    '6405' => "SELECT SQL_CALC_FOUND_ROWS `id`, `id`, `ispublished`, `addts`, `body` FROM `%prefix%comments` ORDER BY `addts` DESC, `id` DESC LIMIT %lim%",
    '6406' => "SELECT `id`, `title_%lang%` AS `title` FROM `%prefix%articles` WHERE `id` = '%id%' LIMIT 1",
    '6407' => "SELECT `id`, `title` FROM `%prefix%photos` WHERE `id` = '%id%' LIMIT 1",
    '6408' => "SELECT `id`, `title_%lang%` AS `title` FROM `%prefix%commodities` WHERE `id` = '%id%' LIMIT 1",
    '6409' => "SELECT SQL_CALC_FOUND_ROWS `id`, `id`, `ispublished`, `addts`, `body` FROM `%prefix%comments` WHERE `objtype` = '%objtype%' ORDER BY `addts` DESC, `id` DESC LIMIT %lim%",
    '6410' => "SELECT SQL_CALC_FOUND_ROWS `id`, `id`, `ispublished`, `addts`, `body` FROM `%prefix%comments` WHERE `objtype` = '%objtype%' AND `objid` = '%objid%' ORDER BY `addts` DESC, `id` DESC LIMIT %lim%",
    '6411' => "SELECT `objtype`, `objid`, `ispublished` FROM `%prefix%comments` WHERE `id` = '%id%' LIMIT 1",
    '6412' => "UPDATE `%prefix%news` SET `comments` = `comments` + %dc% WHERE `id` = '%id%'",
    '6413' => "UPDATE `%prefix%articles` SET `comments` = `comments` + %dc% WHERE `id` = '%id%'",
    '6414' => "UPDATE `%prefix%photos` SET `comments` = `comments` + %dc% WHERE `id` = '%id%'",
    '6415' => "UPDATE `%prefix%commodities` SET `comments` = `comments` + %dc% WHERE `id` = '%id%'",
    '6416' => "UPDATE `%prefix%guide` SET `comments` = `comments` + %dc% WHERE `id` = '%id%'",
    '6417' => "SELECT `id`, `title_%lang%` AS `title` FROM `%prefix%guide` WHERE `id` = '%id%' LIMIT 1",
    /* comments } */

    /* faq { */
    '6600' => "SELECT * FROM `%prefix%faq` WHERE `id` = '%id%' LIMIT 1",
    '6601' => "SELECT MAX(`ord`) FROM `%prefix%faq`",
    '6602' => "SELECT `ord` + 1 AS `ord_next`, `question_%lang%` AS `question` FROM `%prefix%faq` WHERE `id` != '%id%' AND `chapterid` = '%cid%' ORDER BY `ord`",
    '6603' => "SELECT `id`, `ord`, `question_%lang%` AS `question` FROM `%prefix%faq` WHERE `chapterid` = '%cid%' ORDER BY `ord`",
    '6604' => "SELECT `id`, `title_%lang%` AS `title` FROM `%prefix%chapters_faq` ORDER BY `ord`",
    '6605' => "SELECT `id`, `title_%lang%` AS `title` FROM `%prefix%chapters_faq` ORDER BY `ord`",
    '6606' => "SELECT `id` FROM `%prefix%chapters_faq`",
    '6607' => "SELECT `ord` + 1 AS `ord_next`, `question_%lang%` AS `question` FROM `%prefix%faq` WHERE `id` != '%id%' AND `chapterid` = '%cid%' ORDER BY `ord`",
    /* faq } */

    /* consultants { */
    '6800' => "SELECT * FROM `%prefix%consultants` WHERE `id` = '%id%' LIMIT 1",
    '6805' => "SELECT SQL_CALC_FOUND_ROWS `id`, `id`, `ord`, `ispublished`, `title_%lang%` AS `title` FROM `%prefix%consultants` ORDER BY `ord` LIMIT %lim%",
    '6806' => "SELECT SQL_CALC_FOUND_ROWS `id`, `id`, `ord`, `ispublished`, `title_%lang%` AS `title`, `img_s` AS `img` FROM `%prefix%consultants` ORDER BY `ord` LIMIT %lim%",
    '6809' => "SELECT MAX(`ord`) FROM `%prefix%consultants`",
    '6810' => "SELECT `ord` + 1 AS `ord_next`, `title_%lang%` AS `title` FROM `%prefix%consultants` WHERE `id` != '%id%' ORDER BY `ord`",
    '6811' => "INSERT INTO `%prefix%admins` (`ismark`, `srvnotes`, `lastchangets`, `lastchangeadminid`, `lastin`, `lastip`, `fname`, `sname`, `lname`, `emails`, `notify`, `login`, `password`, `permissions`, `params`, `typeid`) VALUES ('0', '', '%lastchangets%', '%lastchangeadminid%', '0', '0', '%fname%', '%sname%', '%lname%', '%emails%', '1', '%login%', '%password%', '100000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000010000000000000000000000000000000000000000000000000000000000000000', 'a:0:{}', '30')",
    '6812' => "UPDATE `%prefix%consultants` SET `adminid` = '%aid%' WHERE `id` = '%id%'",
    '6813' => "SELECT 1 FROM `%prefix%admins` WHERE `login` = '%login%' LIMIT 1",
    '6814' => "UPDATE `%prefix%consultations` SET `consultantid` = 0 WHERE `consultantid` = '%cid%'",
    /* consultants } */

    /* consultations { */
    '7000' => "SELECT * FROM `%prefix%consultations` WHERE `id` = '%id%' LIMIT 1",
    '7001' => "SELECT `id`, `title_%lang%` AS `title` FROM `%prefix%consultants` ORDER BY `ord`",
    '7002' => "SELECT SQL_CALC_FOUND_ROWS `id`, `id`, `ispublished`, `question_%lang%` AS `question`, `addts`, `answerts` FROM `%prefix%consultations` ORDER BY `addts` DESC LIMIT %lim%",
    '7003' => "SELECT `id` FROM `%prefix%consultants` WHERE `adminid` = '%aid%' LIMIT 1",
    '7004' => "SELECT SQL_CALC_FOUND_ROWS `id`, `id`, `ispublished`, `question_%lang%` AS `question`, `addts`, `answerts` FROM `%prefix%consultations` WHERE `consultantid` = '%cid%' ORDER BY `addts` DESC LIMIT %lim%",
    '7005' => "SELECT `title_%lang%` AS `name`, `adminid` FROM `%prefix%consultants` WHERE `id` = '%id%' LIMIT 1",
    '7006' => "SELECT `emails` FROM `%prefix%admins` WHERE `id` = '%id%' LIMIT 1",
    '7007' => "SELECT `text_%lang%` AS `name` FROM `%prefix%texts` WHERE `id` = '%id%' LIMIT 1",
    '7008' => "SELECT `paramvalue` AS `email` FROM `%prefix%params` WHERE `paramname` = '%paramname%' LIMIT 1",
    '7009' => "SELECT `%value%` FROM `%prefix%templates` LIMIT 1",
    '7010' => "UPDATE `%prefix%consultations` SET `answersent` = 1 WHERE `id` = '%id%'",
    /* consultations } */

    /* chapters_faq { */
    '7200' => "SELECT * FROM `%prefix%chapters_faq` WHERE `id` = '%id%' LIMIT 1",
    '7201' => "SELECT MAX(`ord`) FROM `%prefix%chapters_faq`",
    '7202' => "SELECT `ord` + 1 AS `ord_next`, `title_%lang%` AS `title` FROM `%prefix%chapters_faq` WHERE `id` != '%id%' ORDER BY `ord`",
    '7203' => "SELECT `id`, `ord`, `title_%lang%` AS `title` FROM `%prefix%chapters_faq` ORDER BY `ord`",
    '7204' => "DELETE FROM `%prefix%faq` WHERE `chapterid` = '%cid%'",
    /* chapters_faq } */

    /* courses { */
    '9400' => "SELECT * FROM `%prefix%courses` WHERE `id` = '%id%' LIMIT 1",
    '9401' => "DELETE FROM `%prefix%course_chapters` WHERE `courseid` = '%aid%'",
    '9402' => "SELECT `id`, `title_%lang%` AS `title` FROM `%prefix%chapters_courses` ORDER BY `ord`",
    '9403' => "DELETE FROM `%prefix%course_chapters` WHERE `courseid` = '%aid%'",
    '9404' => "INSERT INTO `%prefix%course_chapters` (`courseid`, `chapterid`) VALUES ('%aid%', '%cid%')",
    '9405' => "SELECT SQL_CALC_FOUND_ROWS `id`, `ispublished`, `title_%lang%` AS `title` FROM `%prefix%courses` ORDER BY `title_%lang%` LIMIT %lim%",
    '9406' => "SELECT SQL_CALC_FOUND_ROWS `id`, `ispublished`, `brief_%lang%` AS `title`, `img_s` AS `img` FROM `%prefix%courses` ORDER BY `title_%lang%` LIMIT %lim%",
    '9407' => "SELECT `chapterid` FROM `%prefix%course_chapters` WHERE `courseid` = '%aid%'",
    '9408' => "SELECT `id` FROM `%prefix%chapters_courses` WHERE `url` = '%url%' LIMIT 1",
    '9409' => "DELETE FROM `%prefix%comments` WHERE `objid` = '%oid%' AND `objtype` = 2",
    '9410' => "UPDATE `%prefix%courses` SET `startfile` = '%startfile%', `sizew` = '%sizew%', `sizeh` = '%sizeh%' WHERE `id` = '%id%'",
    /* courses } */

    /* chapters_courses { */
    '9700' => "SELECT * FROM `%prefix%chapters_courses` WHERE `id` = '%id%' LIMIT 1",
    '9701' => "SELECT MAX(`ord`) FROM `%prefix%chapters_courses`",
    '9702' => "SELECT `ord` + 1 AS `ord_next`, `title_%lang%` AS `title` FROM `%prefix%chapters_courses` WHERE `id` != '%id%' ORDER BY `ord`",
    '9703' => "SELECT `id`, `ord`, `title_%lang%` AS `title` FROM `%prefix%chapters_courses` ORDER BY `ord`",
    '9704' => "DELETE FROM `%prefix%course_chapters` WHERE `chapterid` = '%cid%'",
    '9705' => "SELECT `id` FROM `%prefix%courses` WHERE `url` = '%url%' LIMIT 1",
    /* chapters_courses } */

    /* users_olympiad { */
    '9800' => "SELECT * FROM `%prefix%olimpusers` WHERE `id` = '%id%' LIMIT 1",
    '9801' => "UPDATE `%prefix%olimpusers` SET `status` = '%status%' WHERE `id` = '%id%'",
    '9802' => "SELECT `id`, `title_%lang%` AS `title` FROM `%prefix%chapters_olimpusers` ORDER BY `ord`",
    '9803' => "DELETE FROM `%prefix%olimpuser_chapters` WHERE `userid` = '%aid%'",
    '9804' => "INSERT INTO `%prefix%olimpuser_chapters` (`userid`, `chapterid`) VALUES ('%aid%', '%cid%')",
    '9805' => "SELECT SQL_CALC_FOUND_ROWS %fields% FROM `%prefix%olimpusers` ORDER BY %ord% LIMIT %lim%",
    '9806' => "SELECT * FROM `%prefix%admin_olimpuser_params` LIMIT 1",
    '9807' => "SELECT `chapterid` FROM `%prefix%olimpuser_chapters` WHERE `userid` = '%aid%'",
    '9808' => "SELECT `id` FROM `%prefix%chapters_olimpusers` WHERE `url` = '%url%' LIMIT 1",
    '9809' => "SELECT * FROM `%prefix%olimpusers` ORDER BY `addts`, `id`",
    '9810' => "UPDATE `%prefix%olimpusers` SET `status` = '%status%' WHERE `gameid` IN (%ids%)",
    '9811' => "SELECT COUNT(*) AS `count` FROM `%prefix%olimpusers` WHERE `gameid` IN (%ids%)",
    '9812' => "SELECT `gameid`, `name`, `email`, `password` FROM `%prefix%olimpusers` WHERE `gameid` IN (%ids%) AND `status` < 4",
    '9813' => "SELECT `olympiad_approved_sender` AS `sender`, `olympiad_approved_subj` AS `subj`, `olympiad_approved` AS `body` FROM `%prefix%templates`",
    /* users_olympiad } */
);
?>