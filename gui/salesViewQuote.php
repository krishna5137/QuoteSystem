<?php
require '../php/session.php';
require '../php/db.php';

if (!isset($_SESSION['id'])){
		session_unset();
		$_SESSION['invalidAuth'] = true;
		header( 'Location: ./Login.php' ) ;
}

if ($_SESSION['isAdmin']){
		session_unset();
		$_SESSION['invalidAuth'] = true;
		header( 'Location: ./Login.php' ) ;
}
	$quote_id = $_GET['id'];

		$sql = "SELECT * FROM quote where ID='$quote_id'";
		$sql =  $conn->prepare($sql);
		$quote = $sql->execute();
		$quote = $sql->fetch();
	
//get companies
$niu_servername = "blitz.cs.niu.edu";
$niu_username = "student";
$niu_password = "student";
$niu_dbname = "csci467";
try {
    $niu_conn = new PDO("mysql:host=$niu_servername;dbname=$niu_dbname", $niu_username, $niu_password);
    // set the PDO error mode to exception
    $niu_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
catch(PDOException $e)
    {
    echo "NIU DB Connection failed: " . $e->getMessage();
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

    <title>Sales Interface</title>

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
          <a class="navbar-brand" href="./salesHome.php">Sales Interface</a>
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
			<h2>View Quote</h2><br />
		<p class="alert alert-info">This quote has been marked as final and can no longer be edited.</p>
		<form action="" method="post">
		
			<div class="form-group row">
				<label class="col-xs-2 col-form-label">Customer</label>
				<div class="col-xs-10">
					<?php
						$company_id = $quote['Customer_ID'];
						$sql = "SELECT * FROM customers where id='$company_id'";
						$sql =  $niu_conn->prepare($sql);
						$sql->execute();
						$company = $sql->fetch();
					?>
					<input class="form-control" type="text" name="companyID" value="<?php echo $company[1];?>" readonly/>
					</select>
				</div>				
			</div>
			<div class="form-group row">
				<label for="quoteEmail" class="col-xs-2 col-form-label">Email address</label>
				<div class="col-xs-10">
					<input type="email" class="form-control" name="quoteEmail" placeholder="Enter Customer email" value="<?php echo $quote['Customer_Email']; ?>" required="yes" readonly>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-xs-2 col-form-label">Line Items</label>
				<div class="col-xs-10">
					<?php
						$sql = "SELECT * FROM line_item where Quote_ID='$quote_id'";
						$sql =  $conn->prepare($sql);
						$sql->execute();
						$lines = $sql->fetchall();
						foreach ($lines as $line){
						$line_item = $line[1];
						$line_value = $line[2];
						echo "<div style='margin-bottom: 4px;'>
						<input class='form-control' style= 'display:inline; width: 70%;' type='text' name='lineItemDescription[]' value='$line_item' readonly/>
						<input class='form-control' style= 'display:inline; width: 20%;' type='text' name='lineItemPrice[]' value='$ $line_value' readonly/>
						</div>";}
					?>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-xs-2 col-form-label">Secret Notes</label>
				<div class="col-xs-10">
					<textarea class="form-control" name="secretNotes" rows="3" readonly><?php echo $quote['Notes']; ?></textarea>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-xs-2 col-form-label">Is Final</label>
				<div class="col-xs-10">
					<input type="checkbox" class="form-check-input" name="isFinal" checked disabled readonly>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-xs-2 col-form-label">Total Price</label>
				<div class="col-xs-10">
					<p id="total">$ <?php echo $quote['Total'];?></p>
					<input type="hidden" id="total2" name="total" value="0.0" />
				</div>
			</div>
			<br />
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
