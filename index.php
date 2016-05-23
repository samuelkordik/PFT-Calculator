<?php
	include "calc.php";

	$builder = new TestBuilder();

	if (isset($_POST['test'])) {

    $testSuite = new Test($_POST['test']);

    $results = $testSuite->getResults();
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
      <?php if ($results != false) {
        echo $builder->buildResult($_POST['test'], $results);
      } ?>

  	  <ul class="nav nav-tabs" role="tablist">
  	  	<?php
  	  	foreach($builder->getTests() as $testname) {
  	  		echo '<li role="presentation" ';
  	  		echo ($test == $testname) ? 'class="active"' : '';
  	  		echo '><a href="#'.$testname.'" aria-controls="home" role="tab" data-toggle="tab">'.$builder->getTitle($testname).'</a></li>';
  	  	}
  	  	?>
  	  </ul>


  	  <div class="tab-content">

  	  <?php
  	  	foreach($builder->getTests() as $test) {
  	  		$active = ($test == $testname);
  	  		echo  $builder->buildTest($test, $active);
  	  	}
  	  ?>
  	  </div>

  	</div>





</div>
    </main>
            <script type="text/javascript">
                $('.time-control').timepicker({
    	            defaultTime: false,
    	            showMeridian: false,
    	            disableFocus: true,
    	            disableMousewheel: true,
    	            minuteStep: 1,
    	            maxHours: 99
                });
            </script>
   <!--  <footer class="footer">
          <div class="container">
          	<h4 class="text-muted">About</h4>
            <p class="text-muted">
    	Simple calculator to score Army Physical Fitness Test and present results in a copy-and-paste friendly manner. Developed by <a href="http://samuelkordik.com/">Samuel Kordik</a> and licensed under MIT license. See <a href="https://github.com/samuelkordik/PFT-Calculator">GitHub Project</a> to view source code, fork, or share.</p>
          </div>
        </footer> -->

</body>
</html>