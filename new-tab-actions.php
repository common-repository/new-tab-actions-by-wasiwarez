<?php
/*
 * Plugin Name: New Tab Actions by wasiwarez
 * Description: Adds row actions to WordPress post and page list tables for executing in new tab
 * Version: 1.0.17
 * Author: Holger "der_wasi" Wassenhoven
 * Author URI: http://wasiwarez.net/
 * Text Domain: new-tab-actions
 * Domain Path: /languages
 */

defined('ABSPATH') or die('Fehlerhafter Aufruf');

/* Load language file */
function wwnta_loadTextdomain() {
    load_plugin_textdomain('new-tab-actions', FALSE, basename(dirname(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'wwnta_loadTextdomain');

if(is_admin()) {
	add_filter('page_row_actions', 'wwnta_addRowActions', 100, 2);
	add_filter('post_row_actions', 'wwnta_addRowActions', 100, 2);
}
function wwnta_addRowActions($actions, $post) {
	/* Edit in new tab */
	if(isset($actions['edit'])) {
		$htmlTag = wwnta_splitHTML($actions['edit']);
		$openTag = str_replace('>', ' target="_blank">', $htmlTag['open_tag']);
		$content = $htmlTag['content'] . __(' (New tab)', 'new-tab-actions');
		$closeTag = $htmlTag['close_tag'];
		$link = $openTag . $content . $closeTag;
		$offset = array_search('edit',array_keys($actions));
		$offset++;
		$actions = array_slice($actions, 0, $offset, true) +
		array('edit-new-tab' => $link) +
		array_slice($actions, $offset, NULL, true);
	}
	/* View in new tab */
	if(isset($actions['view'])) {
		$htmlTag = wwnta_splitHTML($actions['view']);
		$openTag = str_replace('>', ' target="_blank">', $htmlTag['open_tag']);
		$content = $htmlTag['content'] . __(' (New tab)', 'new-tab-actions');
		$closeTag = $htmlTag['close_tag'];
		$link = $openTag . $content . $closeTag;
		$offset = array_search('view',array_keys($actions));
		$offset++;
		$actions = array_slice($actions, 0, $offset, true) +
		array('view-new-tab' => $link) +
		array_slice($actions, $offset, NULL, true);
	}
	return $actions;
}

function wwnta_splitHTML($html) {
	$string = $html;
	$openTag = substr($string, 0, strpos($string, '>') +1);
	$string = str_replace($openTag, '', $string);
	$content = substr($string, 0, strpos($string, '<'));
	$string = str_replace($content, '', $string);
	$closeTag = $string;
	$return = array(
		'open_tag'	=>	$openTag,
		'content'	=>	$content,
		'close_tag'	=>	$closeTag,
	);
	return $return;
}
?>