<?php
	$standardsfile = fopen("standards.json","r") or die("Unable to open standards definitions.");
	$standards_string = fread($standardsfile, filesize("standards.json"));
	$standards = json_decode($standards_string, true);
	fclose($standardsfile);


	$crumbs = (isset($_COOKIE['crumbs'])) ? json_decode($_COOKIE['crumbs'], true) : array('age'=>0, 'gender'=>'male');



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

		    $crumbs['age'] = $age;
		    $crumbs['gender'] = $gender;

		    setcookie('crumbs', json_encode($crumbs));
	}
?>

<html>
	<head>

<script   src="http://code.jquery.com/jquery-2.2.3.min.js"   integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo="   crossorigin="anonymous"></script>



<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<script src="timepicker.js"></script>
<style>.bootstrap-timepicker{position:relative}.bootstrap-timepicker.pull-right .bootstrap-timepicker-widget.dropdown-menu{left:auto;right:0}.bootstrap-timepicker.pull-right .bootstrap-timepicker-widget.dropdown-menu:before{left:auto;right:12px}.bootstrap-timepicker.pull-right .bootstrap-timepicker-widget.dropdown-menu:after{left:auto;right:13px}.bootstrap-timepicker .input-group-addon{cursor:pointer}.bootstrap-timepicker .input-group-addon i{display:inline-block;width:16px;height:16px}.bootstrap-timepicker-widget.dropdown-menu{padding:4px}.bootstrap-timepicker-widget.dropdown-menu.open{display:inline-block}.bootstrap-timepicker-widget.dropdown-menu:before{border-bottom:7px solid rgba(0,0,0,0.2);border-left:7px solid transparent;border-right:7px solid transparent;content:"";display:inline-block;position:absolute}.bootstrap-timepicker-widget.dropdown-menu:after{border-bottom:6px solid #fff;border-left:6px solid transparent;border-right:6px solid transparent;content:"";display:inline-block;position:absolute}.bootstrap-timepicker-widget.timepicker-orient-left:before{left:6px}.bootstrap-timepicker-widget.timepicker-orient-left:after{left:7px}.bootstrap-timepicker-widget.timepicker-orient-right:before{right:6px}.bootstrap-timepicker-widget.timepicker-orient-right:after{right:7px}.bootstrap-timepicker-widget.timepicker-orient-top:before{top:-7px}.bootstrap-timepicker-widget.timepicker-orient-top:after{top:-6px}.bootstrap-timepicker-widget.timepicker-orient-bottom:before{bottom:-7px;border-bottom:0;border-top:7px solid #999}.bootstrap-timepicker-widget.timepicker-orient-bottom:after{bottom:-6px;border-bottom:0;border-top:6px solid #fff}.bootstrap-timepicker-widget a.btn,.bootstrap-timepicker-widget input{border-radius:4px}.bootstrap-timepicker-widget table{width:100%;margin:0}.bootstrap-timepicker-widget table td{text-align:center;height:30px;margin:0;padding:2px}.bootstrap-timepicker-widget table td:not(.separator){min-width:30px}.bootstrap-timepicker-widget table td span{width:100%}.bootstrap-timepicker-widget table td a{border:1px transparent solid;width:100%;display:inline-block;margin:0;padding:8px 0;outline:0;color:#333}.bootstrap-timepicker-widget table td a:hover{text-decoration:none;background-color:#eee;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;border-color:#ddd}.bootstrap-timepicker-widget table td a i{margin-top:2px;font-size:18px}.bootstrap-timepicker-widget table td input{width:25px;margin:0;text-align:center}.bootstrap-timepicker-widget .modal-content{padding:4px}@media(min-width:767px){.bootstrap-timepicker-widget.modal{width:200px;margin-left:-100px}}@media(max-width:767px){.bootstrap-timepicker{width:100%}.bootstrap-timepicker .dropdown-menu{width:100%}}
/* Sticky footer styles
-------------------------------------------------- */
html {
  position: relative;
  min-height: 100%;
}
body {
  /* Margin bottom by footer height */
  margin-bottom: 60px;
}
.footer {
  position: absolute;
  bottom: 0;
  width: 100%;
  /* Set the fixed height of the footer here */

  background-color: #f5f5f5;
}
.container {
  width: auto;
  max-width: 680px;
  padding: 0 15px;
}
.container .text-muted {
  margin: 20px 0;
}
</style>

</head>
<body>
<main class="bs-masthead" id="content" role="main">
  <div class="container">
    <h1>PFT Calculator</h1>
    <p class="lead">Easy calculator to score the Army Physical Fitness Test</p>

	<?php if (isset($_POST['action'])) { ?>

	<div class="well">
		<?php date_default_timezone_set('America/Chicago');?>
	    <h2>Results on <?php echo date('Y-M-d');?></h2>
		<dl><dt>Pushups</dt><dd><?php echo $pushups;?> (<em><?php echo $pushups_score;?> points</em>)</dd>
			<dt>Situps</dt><dd><?php echo $situps;?> (<em><?php echo $situps_score;?> points</em>)</dd>
			<dt>Two-Mile Run</dt><dd><?php echo $run;?> (<em><?php echo $run_score;?> points</em>)</dd>
			<dt>Total</dt><dd><?php echo $total;?> points</dd>
		</dl>
    </div>

    <?php
	} ?>


    <form id="pft_test" method="post">
	    <input type="hidden" name="action" value="score"/>
	    <div class="form-group">
		    <label for="pushups">Pushups</label>
		    <input type="number" class="form-control" id="pushups" name="pushups" placeholder="Pushups">
		  </div>
		  <div class="form-group">
		    <label for="situps">Situps</label>
		    <input type="number" class="form-control" id="situps" name="situps" placeholder="Situps">
		  </div>

		  <div class="input-group bootstrap-timepicker timepicker">
			  <label for="run">Two-Mile Run</label>
            <input id="run" type="text" name="run" class="form-control input-small">
            <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
        </div>

        <script type="text/javascript">
            $('#run').timepicker({
	            defaultTime: false,
	            showMeridian: false,
	            disableFocus: true,
	            disableMousewheel: true,
	            minuteStep: 1,
	            maxHours: 99
            });
        </script>
		  <div class="form-group">
			  <label for="age">Age Group</label>
			  <select id="age" name="age">
			  	<?php $options = [
				  "0" =>'17&ndash;21',
				  "1" =>'22&ndash;26',
				  "2" =>'27&ndash;31',
				  "3" =>'32&ndash;36',
				  "4" =>'37&ndash;41',
				  "5" =>'42&ndash;46'];
				  foreach ($options as $key => $value) {
				  	echo "<option value=$key";
				  	echo ($crumbs['age]'] == $key) ? " selected='true'>" : ">";
				  	echo "$value</option>";
				  }
				  ?>
			  </select>
		  </div>
		  <div class="form-group">
			  <label for="gender">Gender</label>
			  <select id="gender" name="gender">
			  	  	<?php $options = [
			  		  'male' =>'Male',
			  		  'female' =>'Female'];
			  		  foreach ($options as $key => $value) {
			  		  	echo '<option value="' & $key & '"';
			  		  	echo ($crumbs['age]'] == $key) ? " selected>" : ">";
			  		  	echo "$value</option>";
			  		  }
			  		  ?>

			  	<option value="male" selected>Male</option><option value="female">Female</option></select>
		  </div>
		  <button type="submit" class="btn btn-default">Submit</button>
    </form>
</div>
    </main>
    <footer class="footer">
          <div class="container">
          	<h4 class="text-muted">About</h4>
            <p class="text-muted">
    	Simple calculator to score Army Physical Fitness Test and present results in a copy-and-paste friendly manner. Developed by <a href="http://samuelkordik.com/">Samuel Kordik</a> and licensed under MIT license. See <a href="https://github.com/samuelkordik/PFT-Calculator">GitHub Project</a> to view source code, fork, or share.</p>
          </div>
        </footer>

</body>
</html>