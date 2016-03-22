<?php
include('_pageprefix.php');
if ($_SESSION['wp_username']!='frederickaplan') {
	echo '<div style="position:fixed;top:0;left:0;right:0;bottom:0;color:#c00; text-align:center; padding:100px; font-size:24px;">This poll was closed on 2013-10-30 17:02</div>';
}
function getGrade($crits) {
	$grade = 0;
	if (!in_array('crit1', $crits)) {
		return 0;
	}
	elseif (in_array('crit1', $crits) && !in_array('crit2', $crits) ) {
		return 3;
	}
	else {
		if (in_array('crit1', $crits) && in_array('crit2', $crits) ) {
			$grade = 4;
			for ($i=3; $i<=6; $i++) {
				if (in_array('crit'.$i, $crits)) {
					$grade+=0.5;
				}
			}
			return $grade;
		}
		else return 0;
	}
}

if (isset($_SESSION['wp_username'])) {
	echo '<h2>'.$_SESSION['wp_username'].'’s reviews'.'</h2>';
	echo '<p><em>(Press Save for each review before starting to edit a new one.)</em></p>';
	$criterias = array(
		'crit1' => 'The blog post has only original content (no copy-and-paste from the abstracts or other sources)',
		'crit2' => 'The blog post follows the guidelines of the assignment (it discusses three articles, it identifies a trend)',
		'crit3' => '(Language) The English of the blog post is correct and clearly understandable',
		'crit4' => '(Wordpress) The blog post’s keywords are relevant and the blog post layout is adapted to its content',
		'crit5' => '(Content 1) The blog post is not just a summary of three articles, it really compares them.',
		'crit6' => '(Content 2) The blog post’s content is well argumented and the identified trend is interesting',
	);
	if (isset($_REQUEST['save'])) {
		$c = (array)$_REQUEST['criterias'];
		if ($_SESSION['wp_username']=='frederickaplan') {
			db_u('poll_grades', array('username' => $_SESSION['wp_username'], 'review_id' => $_REQUEST['review_id']), array('criterias' => implode(',', $c), 'grade' => getGrade($c)));
		}
	}
	$polls = db_s('poll_grades', array('username' => $_SESSION['wp_username']), array('review_id' => 'ASC'));
	while ($poll = db_fetch($polls)) {
		echo '<hr/>';
		$post = db_fetch(db_s('poll_posts', array('post_id' => $poll['review_id'])));
		echo '<h3><a href="'.$post['post_url'].'" target="_blank">'.$post['post_title'].'</a></h3>';
		beginForm('post');
			printHiddenInput('review_id', $poll['review_id']);
			printCheckInput('Criterias', 'criterias', @explode(',',$poll['criterias']), $criterias, true);
			if ($poll['grade']>-1) {
				printStaticInput('Grade', getGrade(@explode(',',$poll['criterias'])), 2);
			}
			echo '<br/>';
			printSubmitInput('save', 'Save & Recalculate Grade', true);
		endForm();
	}
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