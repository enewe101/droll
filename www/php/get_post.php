<?php 
require_once $_SERVER['DOCUMENT_ROOT'] . '/common/php/db.php'; 
require_once $_SERVER['DOCUMENT_ROOT'] . '/common/php/auth_lib.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/droll/php/app_lib.php';

$AUTH_CODE = 1;
$auth = is_auth($_SESSION, $AUTH_CODE);

$DEFAULT_NUM_POSTS = 10;
// the functions in this script will be invoked if there is a get variable
// named 'action' that is set.
if(isset($_GET['action'])) {

	$action = $_GET['action'];

	if($action == 'get_latest_post') {
		echo get_latest_post();

	} else if($action == 'get_post') {
		if(!isset($_GET['post_id'])) {
			echo get_latest_post();
		} else {
			$post_id = (int)$_GET['post_id']; 
			if($post_id<1) {
				echo get_latest_post();
			} else {
				echo get_post($post_id);
			}
		}

	} else if($action == 'get_draft') {
		$post_id = get_var('post_id');
		echo get_draft_json($post_id);

	} else if($action == 'get_latest_post') {
		echo get_latest_post();

	} else if($action == 'get_recent_post_listing') {
		if(!isset($_GET['num_posts'])) {
			$num_posts = $DEFAULT_NUM_POSTS;
		} else {
			$num_posts = (int)$_GET['num_posts']; 
			if($num_posts<1) {
				$num_posts = $DEFAULT_NUM_POSTS;
			}
		}
		echo get_recent_post_listing($num_posts);
	
	} else if($action == 'delete_post') {
		$post_id = get_var('post_id');
		echo delete_post($post_id);

	} else {
		$err_msg = 'invalid action specified in $_GET[\'action\']';
		throw new Exception($err_msg);
	}
}
?>
