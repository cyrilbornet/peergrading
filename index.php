<?php
include('_pageprefix.php');

if (isset($_SESSION['wp_username'])) {
	echo '<h2>'.$_SESSION['wp_username'].'</h2>';
	$criterias = array(
		'crit1' => 'The blog post has only original content (no copy-and-paste from the abstracts or other sources)',
		'crit2' => 'The blog post follows the guidelines of the assignment (it discusses three articles, it identifies a trend)',
		'crit3' => '(Language) The English of the blog post is correct and clearly understandable',
		'crit4' => '(Wordpress) The blog post’s keywords are relevant and the blog post layout is adapted to its content',
		'crit5' => '(Content 1) The blog post is not just a summary of three articles, it really compares them.',
		'crit6' => '(Content 2) The blog post’s content is well argumented and the identified trend is interesting',
	);
	$post = db_fetch(db_s('poll_posts', array('user' => $_SESSION['wp_username'])));
	$r = db_fetch(db_s('final_grades', array('review_id' => $post['post_id'])));
	echo '<p>Your grade for your blogpost "<a href="'.$post['post_url'].'">'.$post['post_title'].'</a>": </p><h3>'.$r['grade'].'</h3>';
#	echo '<fieldset><legend>Details</legend>';
#	$given_crits = explode(',', $r['criterias']);
#	foreach ($criterias as $crit_key => $crit_label) {
#		echo '<input type="checkbox" disabled="disabled" '.(in_array($crit_key, $given_crits)?'checked="checked"':'').' /> '.$crit_label.'<br/>';
#	}
#	echo '</fieldset>';
	echo '<br/><br/><br/><hr/>';
	echo '<h3>Poll results</h3>';
	echo '<ul><li>Average grade from peer grading (professor excluded): <b>'.$r['stud_grade'].'</b></li><li><a href="https://en.wikipedia.org/wiki/Standard_deviation">Standard deviation</a>: <b>'.$r['stud_stddev'].'</b></li></ul>';
}
else {
	// Wordpress Connect API login (callback to connected.php, then redirected above here)
	require_once "defines.php";
	$wpcc_state = md5( mt_rand() );
	$_SESSION[ 'wpcc_state' ] = $wpcc_state;
	$params = array(
	  'response_type' => 'code',
	  'client_id' => CLIENT_ID,
	  'state' => $wpcc_state,
	  'redirect_uri' => REDIRECT_URL
	);
	$url_to = AUTHENTICATE_URL .'?'. http_build_query( $params );
	echo '<a href="'.$url_to.'"><img src="//s0.wp.com/i/wpcc-button.png" width="231" /></a>';
}