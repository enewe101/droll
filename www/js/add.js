/* This takes care of adding and previewing posts.  When previewing, a draft
 */

$(document).ready(init);

function init() {
	var post_id = get_current_post();
	get_recent_post_listing(place_listing);
}


