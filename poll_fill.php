<?php

	include('_db/_db.php');
	define('REVIEWS_COUNT', 5);

	$posts = array();
	$users = db_s('poll_posts', array(), array('user' => 'ASC'));
	while ($u = db_fetch($users)) {
		$posts[$u['user']] = $u['post_id'];
	}
	$users = array_keys($posts);
	foreach ($users as $idx => $user) {
		for ($i=0; $i<REVIEWS_COUNT; $i++) {
			db_i('poll_grades', array('username' => $user, 'review_id' => $posts[$users[($idx+$i+1)%count($users)]], 'grade' => -1));
		}
	}

?>