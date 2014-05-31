<?php
	session_start();

	require_once $_SERVER['DOCUMENT_ROOT'] . '/common/php/db.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/common/php/auth_lib.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/droll/php/get_post.php';
	$auth = is_auth($_SESSION, 1);

	// Provided 'draft_id' not supplied in $_GET or $_POST, 
	// default behavior gets the latest post
	$post_id = get_var('draft_id');
	if(is_null($post_id)) {
		$show_type = 'post';
		$post_id = get_cur_post_id(); // post_id from $_GET or latest post

	// but if draft_id is set, we'll show that draft
	} else {
		$post_id = (int)$post_id;
		$show_type = 'draft';
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<script type='text/javascript' src='../../common/js/deparam.js'>
		</script>
		<script type='text/javascript' src='../../common/js/jquery.js'>
		</script>
		<script type='text/javascript' src='../../common/js/utils.js'>
		</script>
		<script type='text/javascript' src='js/app_lib.js'></script>

		<!-- we'll get the post or draft using ajax request.  Tell javascript
			the show_type and the post_id (draft_id) -->
		<script type='text/javascript'>
			var show_type = '<?php echo $show_type; ?>';
			var post_id = <?php echo $post_id; ?>;
			var logged = <?php echo $auth['logged']? 'true':'false';?>; 
			var authed = <?php echo $auth['authed']? 'true':'false';?>; 
		</script>

		<!-- fetching of post or draft done in here after document ready -->
		<script type='text/javascript' src='js/main.js'></script>
		<link rel='stylesheet' type='text/css' href='css/main.css' />
		<link rel='stylesheet' type='text/css' href='../../common/css/basic.css' />
	  <!-- (1) Optimize for mobile versions: http://goo.gl/EOpFl -->
      <meta name="viewport" content="width=device-width, initial-scale=1.0">

      <!-- (1) force latest IE rendering engine: bit.ly/1c8EiC9 -->
      <meta http-equiv="X-UA-Compatible" content="IE=edge">

      <!-- (1) Moot look and feel -->
      <link rel="stylesheet" href="https://cdn.moot.it/1/moot.css">

	  <!-- (1) Moot client application -->
      <script src="https://cdn.moot.it/1/moot.min.js"></script> 

	</head>
	<body>
	<div id='body_in'>

		<?php put_header(); ?>

		<div id='middle'>

			<div id='left'></div>

			<div id='centercol'>
				<div id='page_container'>

					<?php if($auth['authed'] && $show_type=='post') { ?>
					<div id='post_dashboard'>
						<a class='dash_button' id='add_post' href='add.php'>
						+
						</a>
						<a class='dash_button' id='edit_post' 
						href='add.php?post_id=<?php echo $post_id; ?>'>*</a>
						<div class='clear'></div>
					</div>
					<?php } ?>

					<div id='page'></div>
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
			
			<div class='clear'></div>

		</div>

		<div id='footer'>
			<a class="moot" 
			href="https://moot.it/i/shpow/comments/my-large-comment-page">
				Comments for my blog entry
			</a>
		</div>

	</div>
	</body>
</html>
