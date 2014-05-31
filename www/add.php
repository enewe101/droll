<?php
	session_start();

	require_once $_SERVER['DOCUMENT_ROOT'] . '/common/php/db.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/common/php/auth_lib.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/droll/php/app_lib.php';
	$AUTH_CODE = 1;
	$auth = is_auth($_SESSION, $AUTH_CODE);

	$draft_id = get_var('draft_id');
	$post_id = get_var('post_id');

?>

<!DOCTYPE html>
<html>
	<head>
		<!-- php: put authors name and the subject line of the post as title-->

		<script type='text/javascript' src='../../common/js/deparam.js'>
		</script>
		<script type='text/javascript' src='../../common/js/jquery.js'>
		</script>
		<script type='text/javascript' src='../../common/js/utils.js'>
		</script>
		<script type='text/javascript' src='js/app_lib.js'></script>
		<script type='text/javascript'>
			var logged = <?php echo $auth['logged']? 'true':'false';?>; 
			var authed = <?php echo $auth['authed']? 'true':'false';?>; 
		</script>
		<script type='text/javascript' src='js/add.js'></script>
		<link rel='stylesheet' type='text/css' href='css/main.css' />
		<link rel='stylesheet' type='text/css' href='../../common/css/basic.css' />
	</head>
	<body>
	<div id='body_in'>

		<?php put_header(); ?>

		<div id='middle'>

			<div id='left'></div>

			<div id='centercol'>
				<div id='page_container'>
				<div id='page'>

				<?php if($auth['authed']) { 
					if(is_null($draft_id)) {
						if(is_null($post_id)) {
							$draft_id = start_new_draft();
						} else {
							$draft_id = start_new_edit($post_id);
						}
				?>
				<script type='text/javascript'>
					var bas_ref = 'http://shpow.com/droll/add.php';
					var query = '?draft_id=' + <?php echo $draft_id; ?>;
					window.location = bas_ref + query;
				</script>

				<?php 
					} else { 
						$draft = get_draft($draft_id);

						// do escapage for passing variables to javascript
						$subject = js_escape($draft['subject']);
						$summary = js_escape($draft['summary']);
						$post_text = js_escape($draft['post_text']);
				?>

				<form>
					<input type='text' id='subject_text' name='subject_text' 
					class='input_long' />

					<textarea id='summary_text' name='summary_text' 
					class='summary_textarea input_long'></textarea>

					<textarea id='post_text' name='post_text' 
					class='post_textarea input_long'></textarea>

					<button id='preview_button'>preview</button>
					<button id='post_button'>post</button>
				</form>
				<script type='text/javascript'>
					// populate the fields
					$('#subject_text').val("<?php echo $subject;?>");
					$('#summary_text').val("<?php echo $summary;?>");
					$('#post_text').val("<?php echo $post_text;?>");

					// note the draft_id and (if appliccable post_id) to the
					// global scope
					var post_id = <?php echo $draft['post_id'];?>;
					var draft_id = <?php echo $draft['draft_id'];?>;

					// arm the buttons
					$('#post_button').click(function(e) {
						e.preventDefault();
						send_post(go_to_post);
					});
					$('#preview_button').click(function(e) {
						e.preventDefault();
						save_draft(show_preview);
					});
				</script>

				<?php 
					}
				} else {

					if($auth['logged']) { ?>

						<p>
							You are not authorized to add posts.  <br />
							Login as a different user?
						</p>

					<?php } else { ?>

						<p>
							You must login to add posts.
						</p>

				<?php }
					put_login_form(1);
				} ?>


				</div>
				</div>
			</div>

			<div id='rightcol'>
				<div id='recent_post_listing'>
					<div class='text_right'>
						<a href='listing.php' class='text_right'>
							full listing &gt;
						</a>
					</div>
					<h1>recent posts</h1>
					<div id='post_listing_container'></div>
				</div>
			</div>

		</div>

		<div id='footer'>
		</div>

	</div>
	</body>
</html>
