jQuery(document).ready(function($){

	/*

	$.post(ajaxurl, {
		action: 'wptreehouse_badges_refresh_profile' /*our refresh function.
	});

	** This is all we need to make an ajax call to this refresh function
	** but for testing purpose we are going to show when the refresh is done:
	*/

		$.post(ajaxurl, {
		action: 'wptreehouse_badges_refresh_profile'
	}, function( response) {
		console.log('AJAX complete');
	});

});