<?php 
header('Content-type: application/json');
require_once $_SERVER['DOCUMENT_ROOT'] . '/common/php/db.php'; 

$DB_NAME = 'uedwardn_droll';
// the functions in this script will be invoked if there is a get variable
// named 'action' that is set.
if(isset($_POST['action'])) {

	$action = $_POST['action'];

	// post or draft actions require post data
	if($action == 'save_draft' || $action == 'post') {
		if(!isset($_POST['subject_text']) || !isset($_POST['summary_text']) || !isset($_POST['post_text'])) {
			echo 'error post data not all sent';
			exit(0);
		}	
	}

	// make a connection to the db
	$con = connect_db($DB_NAME);

	// get the data that defines the post
	$subject = mysqli_real_escape_string($con, $_POST['subject_text']);
	$summary = mysqli_real_escape_string($con, $_POST['summary_text']);
	$post = mysqli_real_escape_string($con, $_POST['post_text']);

	if($action == 'save_draft') {
		$draft_id = get_var('draft_id');
		$sql = "UPDATE `drafts` SET subject='$subject'";
		$sql .= ", summary='$summary', post_text='$post'";
	   	$sql .= " WHERE draft_id=$draft_id;";

	} else if($action == 'post') {
		$post_id = get_var('post_id');
		if(!is_null($post_id)) {
			$sql = "INSERT INTO posts (post_id, subject, summary, post_text)";
			$sql .= " VALUES ($post_id, '$subject', '$summary', '$post')";
			$sql .= " ON DUPLICATE KEY UPDATE subject=VALUES(subject)";
			$sql .= ", summary=VALUES(summary), post_text=VALUES(post_text);";

		} else {
			$sql = "INSERT INTO posts (subject, summary, post_text)";
			$sql .= " VALUES ('$subject', '$summary', '$post')";
		}
	}

	$result = mysqli_query($con, $sql);
	$err = mysqli_error($con);
	if($err) {
		echo $err;
		exit(0);
	}
	echo mysqli_insert_id($con);
}
?>
