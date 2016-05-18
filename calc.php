<?php
/*
 * Contains the actual business logic.
 */

$standardsfile = fopen("standards.json","r") or die("Unable to open standards definitions.");
$standards_string = fread($standardsfile, filesize("standards.json"));
$standards = json_decode($standards_string, true);
fclose($standardsfile);




function score($exercise, $reps, $gender='male', $age='0') {
	global $standards;

	$dict = $standards[$exercise][$gender][$age];
	$keys = array_keys($dict);

	$min = min($keys);
	$max = max($keys);

	if ($reps > $max) {
		$score = 100;
	} elseif ($reps < $min) {
		$score = 0;
	} else {
		$score = $dict[$reps];
	}
	return($score);
}

function score_run($str_time, $gender, $age) {
	global $standards;
	preg_match('/(^[0-9][0-9]):([0-9][0-9]$)/', $str_time, $matches);
	$run_time = $matches[1]*60+$matches[2];

	$dict = $standards['run'][$gender][$age];

	$score = 0;
	foreach ($dict as $key => $value) {
		if ($run_time < (int) $key) {
			break;
		} else {
			$score = $value;
		}
	}

	return $score;
}

if (isset($_POST['action']) ){
	    ### Score results
	    $pushups = $_POST['pushups'];
	    $situps = $_POST['situps'];
	    $run = $_POST['run'];
	    $age = $_POST['age'];
	    $gender = $_POST['gender'];

	    $total = 0;
	    $pushups_score = score('pushups', $pushups, $gender, $age);
	    $total += $pushups_score;
	    $situps_score = score('situps', $situps, $gender, $age);
	    $total += $situps_score;
	    //$run_score = score('run', $run, $gender, $age);
	    $run_score = score_run($run, $gender, $age);
	    $total += $run_score;

}