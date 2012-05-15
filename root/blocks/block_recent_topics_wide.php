<?php
/**
*
* @package Stargate Portal
* @author  Michael O'Toole - aka Michaelo
* @begin   Sunday, 20th May, 2007
* @copyright (c) 2005-2011 phpbbireland
* @home    http://www.phpbbireland.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* @note: Do not remove this copyright. Just append yours if you have modified it,
*        this is part of the Stargate Portal copyright agreement...
*
* @version $Id$
* 26 April 2012 12:49
*/

/**
* @ignore
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

$phpEx = substr(strrchr(__FILE__, '.'), 1);

$auth->acl($user->data);

$queries = $cached_queries = 0;

global $user, $forum_id, $phpbb_root_path, $phpEx, $SID, $config, $template, $k_config, $k_blocks, $db;
global $web_path;

foreach ($k_blocks as $blk)
{
	if ($blk['html_file_name'] == 'block_recent_topics_wide.html')
	{
		$block_cache_time = $blk['block_cache_time'];
	}
}
$block_cache_time = (isset($block_cache_time) ? $block_cache_time : $k_config['k_block_cache_time_default']);


if(!defined('POST_TOPIC_URL'))
{
	define('POST_TOPIC_URL' , 't');
}
if(!defined('POST_CAT_URL'))
{
	define('POST_CAT_URL', 'c');
}
if(!defined('POST_FORUM_URL'))
{
	define('POST_FORUM_URL', 'f');
}
if(!defined('POST_USERS_URL'))
{
	define('POST_USERS_URL', 'u');
}
if(!defined('POST_POST_URL'))
{
	define('POST_POST_URL', 'p');
}
if(!defined('POST_GROUPS_URL'))
{
	define('POST_GROUPS_URL', 'g');
}

// set up variables used //
$display_this_many = $k_config['k_recent_topics_to_display'];
$forum_count = $row_count = 0;
$except_forum_id = $k_config['k_recent_topics_search_exclude'];
$valid_forum_ids = array();
$k_recent_search_days = $k_config['k_recent_search_days'];
$k_post_types = $k_config['k_post_types'];
$k_recent_topics_per_forum = $k_config['k_recent_topics_per_forum'];

static $last_forum = 0;

$forum_data = array();

$sql = "SELECT html_file_name, scroll, position
	FROM " . K_BLOCKS_TABLE . "
	WHERE html_file_name = 'block_recent_topics_wide.html' ";

if ($result = $db->sql_query($sql, $block_cache_time))
{
	$row = $db->sql_fetchrow($result);
	$scroll = $row['scroll'];
	$display_center = $row['position'];
}
else
{
	trigger_error('ERROR_PORTAL_NEWS');
}
$db->sql_freeresult($result);

$style_row = ($scroll) ? 'scrollwide_' : 'staticwide_';

$sql = "SELECT * FROM ". FORUMS_TABLE . " ORDER BY forum_id";
if (!$result = $db->sql_query($sql, $block_cache_time))
{
	trigger_error($user->lang['ERROR_PORTAL_FORUMS']);
}

/* don't show these (set in ACP) */
$except_forum_ids = explode(",", $except_forum_id);

while ($row = $db->sql_fetchrow($result))
{
	if(!in_array($row['forum_id'], $except_forum_ids))
	{
		$forum_data[] = $row;
		$forum_count++;
	}
}
$db->sql_freeresult($result);

for ($i = 0; $i < $forum_count; $i++)
{
	if ($auth->acl_gets('f_list', 'f_read', $forum_data[$i]['forum_id']))
	{
		$valid_forum_ids[] = (int)$forum_data[$i]['forum_id'];
	}
}

$where_sql = 'WHERE ' . $db->sql_in_set('t.forum_id', $valid_forum_ids);

if ($k_config['k_post_types'])
{
	$types_sql = '';
}
else
{
	$types_sql = "AND t.topic_type = " . FORUM_CAT;
}

$post_time_days = time() - 86400 * $k_recent_search_days;

$sql = 'SELECT SQL_CACHE p.post_id, t.topic_id, t.topic_time, t.topic_title, t.topic_replies, t.forum_id, t.topic_last_post_time, t.topic_last_post_id, t.topic_last_poster_id, t.topic_last_poster_name, t.topic_last_poster_colour, t.topic_type, f.forum_name, p.post_edit_time, p.post_subject, p.post_text, p.post_time, p.bbcode_bitfield, p.bbcode_uid, f.forum_desc
		FROM ' . FORUMS_TABLE . ' f
			LEFT JOIN ' . TOPICS_TABLE . ' t ON (f.forum_id = t.forum_id)
			LEFT JOIN ' . POSTS_TABLE . ' p ON (t.topic_id = p.topic_id)
				' . $where_sql . '
					AND t.topic_approved = 1
					AND p.post_approved = 1
					' . $types_sql . '
					AND p.post_id = t.topic_first_post_id
					AND t.topic_last_post_time >= ' . $post_time_days . '
		ORDER BY t.forum_id, t.topic_last_post_time DESC';

$result = $db->sql_query_limit($sql, $display_this_many, 0, $block_cache_time);

$row = $db->sql_fetchrowset($result);

$db->sql_freeresult($result);

$row_count = count($row);

// display_this_many do we have them?
if ($row_count < $display_this_many)
{
	$display_this_many = $row_count;
}

/*
We need a way to disable scrolling (of any block) if the information retrieved
is less that can be properly displayed in the block. The minimum height of all
blocks that support scrolling is currently set to 160px.
Note as this affects scrolling it is read by the portal.html page...
*/

// Don't scroll recent-topics(RT) if less that 5 posts returned. //
if ($scroll)
{
	if($row_count > 5)
	{
		$template->assign_vars(array(
			'DISABLE_RT_SCROLL'	=> false,
	    ));
	}
	else
	{
		$template->assign_vars(array(
			'DISABLE_RT_SCROLL'	=> true,
	    ));
	}
}

// get the little image if it exists else we will use the default (backward compatibility is a must) ;)
if (file_exists($phpbb_root_path . "{$web_path}styles/" . $user->theme['theme_path'] . '/theme/images/next_line.gif'))
{
	$next_img = '<img src="' . $phpbb_root_path . "{$web_path}styles/" . $user->theme['theme_path'] . '/theme/images/next_line.gif" height="9" width="11" alt="" />';
}
else
{
	$next_img = '<img src="' . $phpbb_root_path . 'images/next_line.gif" height="9" width="11" alt="" />';
}

for ($i = 0; $i < $display_this_many; $i++)
{
	$unique = ($row[$i]['forum_id'] == $last_forum) ? false : true;

	if ($i >= $k_recent_topics_per_forum && $row[$i]['forum_id'] == $row[$i - $k_recent_topics_per_forum]['forum_id'])
	{
		continue;
	}

	$my_title = $row[$i]['topic_title'];

	if (strlen($my_title) > 25)
	{
		sgp_checksize ($my_title, 25);
	}

	$forum_name = $row[$i]['forum_name'];

	if (strlen($forum_name) > 25)
	{
		$forum_name = sgp_checksize ($forum_name, 25);
	}

	$view_topic_url = append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'f=' . $row[$i]['forum_id']);

	if ($row[$i]['post_edit_time'] > $row[$i]['topic_last_post_time'])
	{
		$this_post_time = '*<span style="font-style:italic">' . $user->format_date($row[$i]['post_edit_time']) . '</span>';
	}
	else
	{
		$this_post_time = $user->format_date($row[$i]['topic_last_post_time']);
	}


	$template->assign_block_vars($style_row . 'recent_topic_row', array(
		'LAST_POST_IMG_W'	=> $user->img('icon_topic_newest', 'VIEW_LATEST_POST'),
		'LAST_POST_IMG_W'	=> $next_img,
		'FORUM_W'			=> $forum_name,
		'U_FORUM_W'			=> append_sid("{$phpbb_root_path}viewforum.$phpEx?" . POST_FORUM_URL . '=' . $row[$i]['forum_id']),
		'TITLE_W'			=> censor_text($my_title),
		'U_TITLE_W'			=> $view_topic_url . '&amp;p=' . $row[$i]['topic_last_post_id'] . '#p' . $row[$i]['topic_last_post_id'],
		'POSTER_FULL_W'		=> get_username_string('full', $row[$i]['topic_last_poster_id'], $row[$i]['topic_last_poster_name'], $row[$i]['topic_last_poster_colour']),
		'POSTTIME_W'		=> $this_post_time,
		'S_ROW_COUNT_W'		=> $i,
		'S_UNIQUE_W'		=> $unique,
		'S_TYPE_W'			=> $row[$i]['topic_type'],
		'TOOLTIP_W'			=> bbcode_strip($row[$i]['post_text']),
		'TOOLTIP2_W'		=> bbcode_strip($row[$i]['forum_desc']),
	));

	$last_forum = $row[$i]['forum_id'];
}

if($i > 1)
{
	$post_or_posts = strtolower($user->lang['TOPICS']);
}
else
{
	$post_or_posts = strtolower($user->lang['TOPIC']);
}

$template->assign_vars(array(
	'S_COUNT_RECENT'	=> ($i > 0) ? true : false,
	'SEARCH_TYPE'		=> (!$k_recent_search_days) ? $user->lang['FULL_SEARCH'] : $user->lang['K_RECENT_SEARCH_DAYS'] . $k_recent_search_days,
	'SEARCH_LIMIT'		=> $user->lang['T_LIMITS'] . $k_recent_topics_per_forum . $user->lang['TOPICS_PER_FORUM_DISPLAY'] . $display_this_many . ' ' . $post_or_posts,

	'RECENT_TOPICS_WIDE_DEBUG'	=> sprintf($user->lang['PORTAL_DEBUG_QUERIES'], ($queries) ? $queries : '0', ($cached_queries) ? $cached_queries : '0', ($total_queries) ? $total_queries : '0'),
));

?>