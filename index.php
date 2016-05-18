<?php
	include "calc.php";
	if (isset($_POST['action']) ){
		$apft = new APFTTest();
		$results = $apft->Results();
	} else {
		$results = false;
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
<link rel="stylesheet" href="main.css" />
<style>
</style>

</head>
<body>
<main class="bs-masthead" id="content" role="main">
  <div class="container">
  	<div>

  	  <!-- Nav tabs
  	  <ul class="nav nav-tabs" role="tablist">
  	    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Home</a></li>
  	    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Profile</a></li>
  	    <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Messages</a></li>
  	    <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Settings</a></li>
  	  </ul>

  	  Tab panes
  	  <div class="tab-content">
  	    <div role="tabpanel" class="tab-pane active" id="home">...</div>
  	    <div role="tabpanel" class="tab-pane" id="profile">...</div>
  	    <div role="tabpanel" class="tab-pane" id="messages">...</div>
  	    <div role="tabpanel" class="tab-pane" id="settings">...</div>
  	  </div>

  	</div> -->
    <h1>PFT Calculator</h1>
    <p class="lead">Easy calculator to score the Army Physical Fitness Test</p>

	<?php if ($results != false) { ?>

	<div class="well">
		<?php date_default_timezone_set('America/Chicago');?>
	    <h2>Results on <?php echo date('Y-M-d');?></h2>
		<dl><dt>Pushups</dt><dd><?php echo $results['pushups'];?> (<em><?php echo $results['pushups_score'];?> points</em>)</dd>
			<dt>Situps</dt><dd><?php echo $results['situps'];?> (<em><?php echo $results['situps_score'];?> points</em>)</dd>
			<dt>Two-Mile Run</dt><dd><?php echo $results['run'];?> (<em><?php echo $results['run_score'];?> points</em>)</dd>
			<dt>Total</dt><dd><?php echo $results['total'];?> points</dd>
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

			  	<?php $options = array(
				  "0" =>"17&ndash;21",
				  "1" =>"22&ndash;26",
				  "2" =>"27&ndash;31",
				  "3" =>"32&ndash;36",
				  "4" =>"37&ndash;41",
				  "5" =>"42&ndash;46");

			  		?>
			  	<select id="age" name="age">
			  		<?php
				  foreach ($options as $key => $value) {
				  	echo '<option value=' . $key;
				  	echo ($crumbs['age]'] == $key) ? " selected='true'>" : ">";
			  		echo $value . '</option>';
				  }
				  ?>
				</select>

		  </div>
		  <div class="form-group">
			  <label for="gender">Gender</label>
			  <select id="gender" name="gender">
			  	  	<?php $options = array(
			  		  'male' =>'Male',
			  		  'female' =>'Female');
			  		  foreach ($options as $key => $value) {
			  		  	echo '<option value="' . $key & '"';
			  		  	echo ($crumbs['age]'] == $key) ? " selected>" : ">";
			  		  	echo $value . '</option>';
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