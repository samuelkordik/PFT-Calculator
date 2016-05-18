<?php
/*
 * Contains the actual business logic.
 */

class Standards {
	private $_standards = array();

	function __construct() {
		$standardsfile = fopen("standards.json","r") or die("Unable to open standards definitions.");
		$standards_string = fread($standardsfile, filesize("standards.json"));
		$this->_standards = json_decode($standards_string, true);
		fclose($standardsfile);
	}

	function getStandards($exercise, $gender, $age) {
		return $this->_standards[$exercise][$gender][$age];
	}
}

class Test {
	private $_standards;

	function __construct($testtype = 'apft') {
		$this->_standards = new Standards();
	}

	function score($exercise, $reps, $gender='male', $age='0') {
		$dict = $this->_standards->getStandards($exercise, $gender, $age);
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
		preg_match('/(^[0-9][0-9]):([0-9][0-9]$)/', $str_time, $matches);
		$run_time = $matches[1]*60+$matches[2];

		$dict = $this->_standards->getStandards('run', $gender, $age);

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
}

class APFTTest extends Test {

	function __construct() {
		parent::__construct('apft');
	}

	function Results () {
		$ret = array();
	    ### Score results
	    $ret['pushups'] = $_POST['pushups'];
	    $ret['situps'] = $_POST['situps'];
	    $ret['run'] = $_POST['run'];
	    $age = $_POST['age'];
	    $gender = $_POST['gender'];

	    $total = 0;
	    $ret['pushups_score'] = $this->score('pushups', $ret['pushups'], $gender, $age);
	    $total += $ret['pushups_score'];
	    $ret['situps_score'] = $this->score('situps', $ret['situps'], $gender, $age);
	    $total += $ret['situps_score'];
	    //$run_score = score('run', $run, $gender, $age);
	    $ret['run_score'] = $this->score_run($ret['run'], $gender, $age);
	    $total += $ret['run_score'];
	    $ret['total'] = $total;
		return $ret;
	}
}



