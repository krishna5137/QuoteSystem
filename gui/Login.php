<?php
require '../php/session.php';
require '../php/db.php';

//login function
if (isset($_POST['inputUsername'])){
	$username = $_POST['inputUsername'];
	$password = $_POST['inputPassword'];
	$sql = "SELECT First_Name, Last_Name, Is_Admin, ID FROM employee WHERE Username='$username' and Password='$password'";
	$sql =  $conn->prepare($sql);
	$sql->execute();
	$result = $sql->fetchAll();
	if(count($result)>0){
		$_SESSION['name'] = $result[0][0]. " " . $result[0][1];
		$_SESSION['isAdmin'] = $result[0][2];
		$_SESSION['id'] = $result[0][3];
	}
	else
		$_SESSION['loginError']=true;
}

//if a user is already logged in
if (isset($_SESSION['name'])){
	if ($_SESSION['isAdmin'])
		header( 'Location: ./adminHome.php' ) ;
	else
		header( 'Location: ./salesHome.php' ) ;
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

    <title>Quote System</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="../css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/navbar-static-top.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
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
          <a class="navbar-brand" href="#">Quote System</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
        </div><!--/.nav-collapse -->
      </div>
    </nav>


    <div class="container">

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <div class="container" style="margin: 0 auto; width: 50%;">
			<form class="form-signin" action="" method="post">
			<?php if(isset($_SESSION['invalidAuth'])) : ?> 
				<p class="alert alert-danger">You have to login before accessing that page</p>
			<?php unset($_SESSION['invalidAuth']); endif; ?>
			<?php if(isset($_SESSION['loginError'])) : ?> 
				<p class="alert alert-danger">Invalid Username or Password. Please try again.</p>
			<?php unset($_SESSION['loginError']); endif; ?>
			<?php if(isset($_SESSION['logout'])) : ?> 
				<p class="alert alert-success">You have been successfully logged out.</p>
			<?php unset($_SESSION['logout']); endif; ?>
			<h2 class="form-signin-heading">Please sign in</h2>
			<label for="inputUsername" class="sr-only">Username</label>
			<input type="text" id="inputUsername" name="inputUsername" class="form-control" placeholder="Username" required="" autofocus="">
			<label for="inputPassword" class="sr-only">Password</label>
			<input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Password" required=""><br />
			<input class="btn btn-lg btn-primary btn-block" type="submit" value="Sign In"/>
		</div>
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
