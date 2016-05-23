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

	function getStandards($test, $exercise, $gender, $age) {
		$return = $this->_standards[$test][$exercise][$gender][$age];

		return $return;
	}

}

class TestBuilder {
	private $_blueprint = array();

	function __construct() {
		$testfile = fopen("tests.json","r") or die("Unable to open test definitions.");
		$blueprint_string = fread($testfile, filesize("tests.json"));
		$this->_blueprint = json_decode($blueprint_string, true);
		fclose($testfile);

	}

	/**
	 * Returns list of tests included in definition file.
	 */
	function getTests() {
		return array_keys($this->_blueprint);
	}

	/**
	 * Returns title information for designated test.
	 */
	function getTitle($test) {
		return $this->_blueprint[$test]['title'];
	}

	/**
	 * Returns html string for form for test specified.
	 */
	function buildTest($test, $active) {
		$def = $this->_blueprint[$test]['components'];
		$html = '';
		$html .= '<div role="tabpanel" class="tab-pane';
		$html .= ($test == $active) ? ' active' : '';
		$html .= '" id="'.$test.'">';
		$html .= '<h1>'.$this->getTitle($test).'</h1>';
		$html .= '<form method="post"><input type="hidden" name="action" value="score"/><input type="hidden" value="'.$test.'" name="test"/>';

		foreach($def as $partname => $part) {
			$html .= '<div class="form-group">';
			$html .= '<label for="'.$partname.'">'.$part['label'].'</label>';

			switch($part['type']) {
				case 'reps':
				$html .= '<input type="number" class="form-control" id="'.$partname.'" name="'.$partname.'" placeholder="'.$part['label'].'">';
					break;
				case 'time':
				$html .= '<input id="'.$partname.'" type="text" name="'.$partname.'" class="form-control input-small time-control">';
					break;
				case 'age':
					$html .= '<select id="age" name="age">';
					foreach($part['options'] as $key=>$value) {
					  	$html .= '<option value=' . $key;
					  	$html .= ($crumbs['age]'] == $key) ? " selected='true'>" : ">";
				  		$html .= $value . '</option>';
					}
					$html .= '</select>';
				break;
				case 'gender':
				$html .= '<select id="gender" name="gender"><option value="male">Male</option><option value="female">Female</option></select>';
			}

		  	$html .= '</div>';
		}
		$html .= '<button type="submit" class="btn btn-default">Submit</button></form>';
		$html .= '</div>';
		return $html;
	}

	/**
	 * Returns html string for results
	 */
	function buildResult($test, $results) {

		$def = $this->_blueprint[$test]['components'];
		date_default_timezone_set('America/Chicago');
		$html = '<div class="well">';
		$html .= '<h4>Results for '.$this->getTitle($test).' on '. date('Y-M-d').'</h4>';
		$html .= '<dl>';
		foreach($def as $partname => $part) {
			if ($part['type'] != 'age' && $part['type'] != 'gender') {
			$html .= '<dt>'.$part['label'].'</dt><dd>';
			$html .= $results[$partname]['actual'];
			$html .= (isset($results[$partname]['score'])) ?
				'(<em>'.$results[$partname]['score'].' points</em>)' : '';
			$html .= '</dd>';
		}
		}
		$html .= '<dt>Total</dt><dd>'.$results['total'].' points</dd>';
		$html .= '</dl></div>';
		return $html;
	}

	/**
	 * Returns component list for scoring test
	 */
	function componentList($test) {
		return $this->_blueprint[$test]['components'];
	}

}

class Test {
	private $_standards;
	private $_testtype;
	private $_builder;

	function __construct($testtype = 'apft') {
		$this->_standards =  new Standards();
		$this->_builder = new TestBuilder();
		$this->_testtype = $testtype;

	}

	function score_reps($exercise, $reps, $gender='male', $age='0') {

		$dict = $this->_standards->getStandards($this->_testtype, $exercise, $gender, $age);
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

	function score_time($exercise, $str_time,  $gender, $age) {
		preg_match('/(^[0-9][0-9]):([0-9][0-9]$)/', $str_time, $matches);
		$run_time = $matches[1]*60+$matches[2];

		$dict = $this->_standards->getStandards($this->_testtype, $exercise, $gender, $age);

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

	function getResults() {
		$components = $this->_builder->componentList($this->_testtype);
		$ret = array();
		$total = 0;
		$gender = $_POST['gender'];
		$age = $_POST['age'];

		foreach($components as $partname => $part) {
			$ret[$partname]["actual"] = $_POST[$partname];
			switch ($part['type']) {
				case 'reps':
					$ret[$partname]["score"] = $this->score_reps($partname, $_POST[$partname], $gender, $age);
					$total += $ret[$partname]["score"];
					break;
				case 'time':
					$ret[$partname]["score"] = $this->score_time($partname, $_POST[$partname], $gender, $age);
					$total += $ret[$partname]["score"];
					break;
			}
		}
		$ret['total'] = $total;
		return $ret;
	}
}