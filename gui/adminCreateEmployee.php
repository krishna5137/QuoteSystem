<?php
require '../php/session.php';
require '../php/db.php';

if (!isset($_SESSION['id'])){
		session_unset();
		$_SESSION['invalidAuth'] = true;
		header( 'Location: ./Login.php' ) ;
}

if (!$_SESSION['isAdmin']){
		session_unset();
		$_SESSION['invalidAuth'] = true;
		header( 'Location: ./Login.php' ) ;
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin Interface</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="../css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/navbar-static-top.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <!-- Static navbar -->
    <nav class="navbar navbar-default navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="adminHome.php">Admin Interface</a>
		  <ul class="nav navbar-nav">
            <li class="nav-item active">
              <a class="nav-link" href="adminManageEmployees.php">Manage Employees</a>
            </li>
		  </ul>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a>Welcome <?php echo $_SESSION['name'];?></a></li>
            <li><a href="Logout.php">Logout</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>


    <div class="container">

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
		<h2>Create Employee</h2><br />
		<form action="adminManageEmployees.php" method="post">
			<input type="hidden" name="createEmployee" value="1"/>
			<div class="form-group row">
				<label class="col-xs-2 col-form-label">First Name</label>
				<div class="col-xs-10">
					<input type="text" class="form-control" name="employeeFirstName" required="yes">
				</div>				
			</div>
			<div class="form-group row">
				<label class="col-xs-2 col-form-label">Last Name</label>
				<div class="col-xs-10">
					<input type="text" class="form-control" name="employeeLastName" required="yes">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-xs-2 col-form-label">Username</label>
				<div class="col-xs-10">
					<input type="text" class="form-control" name="employeeUsername" required="yes">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-xs-2 col-form-label">Password</label>
				<div class="col-xs-10">
					<input type="password" class="form-control" name="employeePassword" required="yes">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-xs-2 col-form-label">Address</label>
				<div class="col-xs-10">
					<input type="text" class="form-control" name="employeeAddress" required="yes">
				</div>
			</div>
			<input class="btn btn-lg btn-primary" type="submit" value="Create Employee" />
		</form>
	  </div>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../js/jquery.min.js"><\/script>')</script>
    <script src="../js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
