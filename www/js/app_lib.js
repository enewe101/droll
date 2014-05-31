function notify(msg) {
	$('#notification_target').html('&nbsp;' + msg + '&nbsp;'); 
}

function get_current_post() {
	var post_id = null;
	if(window.location.search) {
		// shed the leading '?' character from the param_string
		var param_string = window.location.search.split("?")[1];
		try {
			var params = deParam(param_string);
			if('post_id' in params) {
				post_id = parseInt(params['post_id']);
			}
		} catch(e) {
		}
	}
	return post_id;
}

function send_post(callback) {
	data_obj = get_post_obj();
	data_obj['action'] = 'post';
	if(isUint(post_id)) {
		data_obj['post_id'] = post_id;
	}
		
	$.ajax({
		'url': 'http://shpow.com/droll/php/add_post.php',
		'data': data_obj,
		'type': 'POST',
		'dataType': 'text', // this should send back a key
		'success': callback,
		'error': function(xhr, status) {
			alert('sorry, failed: ' + status);
		}
	});
}

function save_draft(callback) {
	callback = callback || function(){};
	data_obj = get_post_obj();
	data_obj['action'] = 'save_draft';
	data_obj['draft_id'] = draft_id; // global set in add.php

	$.ajax({
		'url': 'http://shpow.com/droll/php/add_post.php',
		'data': data_obj,
		'type': 'POST',
		'dataType': 'text', // this should send back a draft_id
		'success': callback,
		'error': function(xhr, status) {
			alert('sorry, failed: ' + status);
		}
	});
}

function get_post_obj() {
	return {
		"subject_text" : $('#subject_text').val(),
		"summary_text" : $('#summary_text').val(),
		"post_text" : $('#post_text').val()
	};
}


function go_to_post(text) {
	try {
		post_id = parseInt(text);
	} catch(e) {
		alert(e);
		return;
	}
	var url = 'index.php?post_id=' + post_id;
	window.location.href = url;
}

function show_preview() {
	var base_ref = 'http://shpow.com/droll';
	var query = '?draft_id=' + draft_id;
	win = window.open(base_ref + query, '_blank');
	win.focus();
}


function get_recent_post_listing(callback) {
	data = {'action': 'get_recent_post_listing'};

	// the recent post listing will actually contain posts near to the one
	// currently being viewed.  If the latest is being viewed, we may not 
	// know the current post id (it will return null), but the default 
	// behavior of get_post.php will give the latest listings
	current_post_id = get_current_post();
	if(isInt(current_post_id)) {
		data['current_post_id'] = current_post_id;
	}

	$.ajax({
		'url': 'http://shpow.com/droll/php/get_post.php',
		'data': data,
		'type': 'GET',
		'dataType': 'json',
		'success': callback,
		'error': function(xhr, status) {
			alert('sorry, failed: ' + status);
		}
	})
}


function get_post(post_id, show_type) {
	show_type = show_type || 'post';

	var action = 'get_' + show_type;
	data_obj = {'action': action};
	if(isUint(post_id)) {
		data_obj['post_id'] = post_id;
	}

	$.ajax({
		'url': 'http://shpow.com/droll/php/get_post.php',
		'data': data_obj,
		'type': 'GET',
		'dataType': 'json',
		'success': place_post,
		'error': function(xhr, status) {
			place_post({
				'error':'there was a problem fetching the post: ' + status});
		}
	})
}


function get_latest_post() {
	$.ajax({
		'url': 'http://shpow.com/droll/php/get_post.php',
		'data': {'action': 'get_latest_post'},
		'type': 'GET',
		'dataType': 'json',
		'success': place_post,
		'error': function(xhr, status) {
			alert('sorry, failed: ' + status);
		}
	})
}

function delete_post(post_id, callback) {
	callback = callback || function(){};
	var data = {'action':'delete_post', 'post_id':post_id};
	$.ajax({
		'url': 'http://shpow.com/droll/php/get_post.php',
		'data': data,
		'type': 'GET',
		'dataType': 'text',
		'success': callback,
		'error': function(xhr, status) {
			alert('sorry, failed: ' + status);
		}
	})
}

function do_reload() {
	window.location.reload();
}

function place_listing(json_listing) {
	put_listing(json_listing, $('#post_listing_container'));
}

function place_full_listing(json_listing) {
	put_listing(json_listing, $('#page'));
}

function put_listing(json_listing, jquery_element) {
	$.each(json_listing, function(index, listing) {
		var post_date = $("<div/>", {"class": "post_date"})
		post_date.html(listing['date']); 

		var post_subject = $("<a/>", {
			"class": "post_subject",
			"href": "http://shpow.com/droll?post_id=" + listing['post_id']
		});
		post_subject.html(listing['subject']);

		if(authed) {
			var del_post = $("<div/>", {
				"class": "del_post",
				"data": {
					'post_id':listing['post_id'],
					'subject':listing['subject']
				},
				"html": 'x'
				});
			del_post.click(function(e) {
				subject = $(this).data('subject');
				post_id = $(this).data('post_id');
				do_delete = confirm(
					'are you sure you want to delete ' + subject + '?');
				if(do_delete) {
					delete_post(post_id, do_reload);
				}
			});
		}
		var clear_div = make_clear_div();
		var listing = $("<div/>", {"class": "listing"});
		if(authed) {
			listing.append([post_date, post_subject, del_post, clear_div]);
		} else {
			listing.append([post_date, post_subject, clear_div]);
		}
		jquery_element.append(listing);
	});
}

function make_clear_div() {
	return $("<div/>", {"class":"clear"});
}

function place_post(json_post) {
	if('error' in json_post) {
		$('#page').html(json_post['error']);
	}
	if(json_post === false) {
		$('#page').html(
			'<h1>You are not authorized to view this content</h1>');
	}
	if(json_post.length < 1) {
		var new_html = "<div class='summary'>Bad post id!</div>";
		$('#page').html(new_html);
	}
	var new_post = json_post[0];
	var new_html = "<h1>" + new_post['subject'] + "</h1>";
	new_html += "<div class='summary'>" + new_post['summary'] + "</div>";
	new_html += "<div class='post'>" + new_post['post_text'] + "</div>";
	$('#page').html(new_html);
}


