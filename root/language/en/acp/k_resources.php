<?php
/**
*
* @package Kiss Portal Engine (acp_k_resources) (English)
*
* @package language
* @version $Id:$ 1.0.16
* @copyright (c) 2005-2011 Michael O'Toole (mike@phpbbireland.com)
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(

	'ACP_K_RES_WORDS'			=> 'Resources',
	'ACP_K_ADMIN_REFERRALS'		=> 'Manage Resource words',
	'ADD_VARIABLE'				=> 'Add Variable',
	'CONFIG'					=> 'config',
	'DISABLE_MARKED'			=> 'Disable_marked',
	'ENABLE_MARKED'				=> 'Enable marked',
	'ID'						=> 'ID',
	'K_CONFIG'					=> 'k_config',
	'LINE'						=> ', line ',
	'NA'						=> '...',
	'NEW'						=> 'New',
	'NEW_WORD'					=> 'A new word',
	'NEW_WORD_ADD'				=> 'Add leading $ for variable',
	'NO_ITEMS_MARKED'			=> 'No items marked.',
	'OPTION'					=> 'Option',
	'ORDER'						=> 'Order',
	'PROCESS_REPORT'			=> 'Process report: %1$s',
	'PLEASE_CONFIRM'			=> 'Please confirm.',
	'PLEASE_CONFIRM_ADD'		=> 'Confirm add word?',
	'PLEASE_CONFIRM_UPDATE'		=> 'Update words?',
	'PLEASE_CONFIRM_DELETE'		=> 'Confirm delete?',

	'R'								=> 'Reserved',
	'REFERRALS_MANAGEMENT'			=> 'Referrals management.',
	'REFERRALS_MANAGEMENT_EXPLAIN'	=> 'Here you can manage HTTP Referrals stored in your database.',

	'REPORT'					=> 'Last process report',
	'RESERVED'					=> 'Reserved',
	'RESERVED_WORDS'			=> 'Reserved word',
	'SAVE_CURRENT'				=> 'Save current',
	'SELECT_FILTER'				=> 'Select filter',
	'SELECT_SORT_METHOD'		=> 'Select sort method',
	'SHOW_BOTH_TYPES'			=> 'Show both types',
	'SORT'						=> 'Sort',
	'SORT_ASCENDING'			=> 'Ascending',
	'SORT_DESCENDING'			=> 'Descending',
	'SWITCH'					=> 'Switch types',
	'SWITCH_A'					=> 'Both',
	'SWITCH_R'					=> 'Reserved',
	'SWITCH_TO_WORDS'			=> 'Switch to words',
	'SWITCH_TO_VARIABLES'		=> 'Switch to variables',
	'SWITCH_V'					=> 'Variables',
	'TABLE'						=> 'Table',
	'TYPE'						=> 'Type',
	'V'							=> 'Variable',
	'VAR'						=> 'Variable',
	'VARIABLE'					=> 'A variable',
	'VAR_NAME'					=> 'Variable name',
	'WORDS'						=> 'Reserved',
	'UNKNOWN'					=> 'Unknown',
	'VAR_NOT_FOUND'				=> '<strong>%s</strong> is not a valid config variable... Add action was aborted!',
	'VAR_ADDED'					=> '<strong>%s</strong> added!',

	'TITLE' 		=> 'Portal Variables Manager',
	'TITLE_EXPLAIN'	=> 'When you add or edit a web page, you can pass additional information in the form of variable data taken from the $config or $k_config tables.<br />Before these variables can be accessed, you must first specify them using this form. The variable name will be replaced with the variable data automatically.<br />Enter the variable name exactly as it appears in the database (normally lower case without spaces). If the variable does not exits it will be ignored....',
	'TITLE_ADD'		=> 'Add Variable',
));

?>