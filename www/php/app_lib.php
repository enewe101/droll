<?php 
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/common/php/db.php'; 

function get_latest_post() {
	$con = connect_db('uedwardn_droll');

	$query = 'SELECT * FROM posts ORDER BY `timestamp` DESC LIMIT 0,1;';

	$result = mysqli_query($con, $query);
	$err = mysqli_error($con);
	if($err) {
		echo $err;
		exit(0);
	}

	return result2json($result);
}

function start_new_draft() {
	$con = connect_db('uedwardn_droll');

	$query = "INSERT INTO drafts SET subject='new post';";

	$result = mysqli_query($con, $query);
	$err = mysqli_error($con);
	if($err) {
		echo $err;
		exit(0);
	}

	return mysqli_insert_id($con);
}

function get_draft($draft_id) {
	$con = connect_db('uedwardn_droll');

	$query = "SELECT * FROM drafts WHERE draft_id=$draft_id;";

	$result = mysqli_query($con, $query);
	$err = mysqli_error($con);
	if($err) {
		echo $err;
		exit(0);
	}

	return mysqli_fetch_assoc($result);
}

function start_new_edit($post_id) {
	$con = connect_db('uedwardn_droll');

	$query = "INSERT INTO drafts (post_id, subject, summary, post_text)";
	$query .= " SELECT post_id, subject, summary, post_text FROM posts";
   	$query .= " WHERE post_id=$post_id";

	$result = mysqli_query($con, $query);

	$err = mysqli_error($con);
	if($err) {
		echo $err;
		exit(0);
	}

	return mysqli_insert_id($con);
}

function get_cur_post_id() {
	$post_id = get_var('post_id');
	if(!is_null($post_id)) {
		return (int)$post_id;
	}

	$con = connect_db('uedwardn_droll');

	$query = "SELECT post_id FROM posts ORDER BY `timestamp`";
	$query .= " DESC LIMIT 0,1;";

	$result = mysqli_query($con, $query);
	$err = mysqli_error($con);
	if($err) {
		echo $err;
		exit(0);
	}

	$row = mysqli_fetch_array($result);
	return (int)$row[0];
}

function put_header() {
	?>

	<div id='notification_wrapper' class='text_center'>
		<span id='notification_target'>
		</span>
	</div>

	<div id='header'>
		<div id='banner'>
			<div id='logo'>
			</div>
			<div id='logging'>

			<?php put_login_widget(); ?>

			</div>
			<div class='clear'></div>
		</div>
	</div>

	<?php
}

function get_post($post_id) {
	$con = connect_db('uedwardn_droll');

	$query = "SELECT * FROM posts WHERE post_id=$post_id;";

	$result = mysqli_query($con, $query);
	$err = mysqli_error($con);
	if($err) {
		echo $err;
		exit(0);
	}

	return result2json($result);
}

function get_draft_json($draft_id) {
	// getting drafts requires an authenticated user
	$AUTH_CODE = 1;
	$auth = is_auth($_SESSION, $AUTH_CODE);
	if(!$auth['authed']) {
		echo 'false';
		exit(0);
	}

	$con = connect_db('uedwardn_droll');
	$query = "SELECT * FROM drafts WHERE draft_id=$draft_id;";
	$result = mysqli_query($con, $query);
	$err = mysqli_error($con);
	if($err) {
		echo $err;
		exit(0);
	}

	return result2json($result);
}

function delete_post($post_id) {
	$con = connect_db('uedwardn_droll');

	$sql = "DELETE FROM posts WHERE post_id=$post_id";
	$result = mysqli_query($con, $sql);
	$err = mysqli_error($con);
	if($err) {
		echo $err;
		exit(0);
	}
}

function get_recent_post_listing($num_posts) {
	$con = connect_db('uedwardn_droll');

	$query = "SELECT post_id, DATE_FORMAT(timestamp, '%e %b %Y') as date";
	$query .= ", subject FROM posts ORDER BY `timestamp`";
	$query .= " DESC LIMIT 0,$num_posts;";

	$result = mysqli_query($con, $query);
	$err = mysqli_error($con);
	if($err) {
		echo $err;
		exit(0);
	}

	return result2json($result);
}

function htmlify() {
	$para_pattern = "/\r?\n/";
	$para_replacement = '<br \><br \>';
	$test_str = "a\nc";
	$str = preg_replace($para_pattern, $para_replacement, $test_str);
	return $str;
}
?>
