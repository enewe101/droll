<?php
	session_start();

	require_once $_SERVER['DOCUMENT_ROOT'] . '/common/php/db.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/common/php/auth_lib.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/droll/php/app_lib.php';
	$AUTH_CODE = 1;
	$auth = is_auth($_SESSION, $AUTH_CODE);


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


				</div>
				<script type='text/javascript'>
					get_recent_post_listing(place_full_listing);
				</script>
				</div>
			</div>

			<div id='rightcol'>
			</div>

		</div>

		<div id='footer'>
		</div>

	</div>
	</body>
</html>
