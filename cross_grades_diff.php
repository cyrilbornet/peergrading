<?php
include('_db/_db.php');
header('Content-Type: text/plain');

$students = db_x('SELECT DISTINCT(username) AS u FROM poll_grades WHERE username!="frederickaplan";', false);
while ($student = db_fetch($students)) {
	$stud_post = db_fetch(db_s('poll_posts', array('user' => $student['u'])));
	$stud_post_grade = db_fetch(db_x('select avg(grade) as g from poll_grades where username!="frederickaplan" AND grade > 0 and review_id='.$stud_post['post_id'].';', false));
	$prof_post_grade = db_fetch(db_x('select avg(grade) as g from poll_grades where username="frederickaplan" AND review_id='.$stud_post['post_id'].';', false));
	$given_grades_diff = array();
	$given_grades_r = db_s('poll_grades', array('username' => $student['u']));
	while ($grade = db_fetch($given_grades_r)) {
		$post_grade = db_fetch(db_x('select avg(grade) as g from poll_grades where username!="frederickaplan" and username!="'.$student['u'].'" and review_id='.$grade['review_id'].' and grade > 0;', false));
		$given_grades_diff[] = ($grade['grade'] - $post_grade['g']);
	}
	echo $stud_post['post_id']."\t".$student['u']."\t".array_sum($given_grades_diff)."\t".$stud_post_grade['g']."\t".$prof_post_grade['g']."\n";
}