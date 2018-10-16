<?php
require '../php/session.php';
require '../php/db.php';
require '../php/niu_db.php';
require '../php/QuoteController.php';

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

	$id = $_SESSION['id'];
	//update quote
	if (isset($_POST['companyID']) && isset($_POST['quote_id'])){
		$quote_id = $_POST['quote_id'];
		$updated_quote = true;
		$company_id = $_POST['companyID'];
		$quote_email = $_POST['quoteEmail'];
		$secret_notes = $_POST['secretNotes'];
		$is_final = false;
		if(isset($_POST['isFinal'])) $is_final = true;
		$total = $_POST['total'];
		$line_item = $_POST['lineItemDescription'];
		$line_price = $_POST['lineItemPrice'];
		$total = $_POST['total'];
		updateQuote($quote_email,$company_id,$is_final,$secret_notes,$total,$quote_id,$line_item,$line_price,$conn);
	}
	//add quote
	if (isset($_POST['companyID']) && !isset($_POST['quote_id'])){
		$company_id = $_POST['companyID'];
		$quote_email = $_POST['quoteEmail'];
		$secret_notes = $_POST['secretNotes'];
		$is_final = false;
		if(isset($_POST['isFinal'])) $is_final = true;
		$total = $_POST['total'];
		$line_item = $_POST['lineItemDescription'];
		$line_price = $_POST['lineItemPrice'];
		$total = $_POST['total'];
		addQuote($quote_email,$company_id,$is_final,$secret_notes,$id,$total,$line_item,$line_price,$conn);
		$new_quote = true;
	}
	
	$result = getQuote($id,$conn);
	$quote_count = count($result);
	
	
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
          <a class="navbar-brand" href="#">Sales Interface</a>
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
        <h2>Quotes</h2>
		<?php if(isset($new_quote)) : ?>
			<p class="alert alert-success">Your quote was successfully created</p>
		<?php $new_quote=false; endif; ?>
		<?php if(isset($updated_quote)) : ?>
			<p class="alert alert-success">Your quote was successfully updated</p>
		<?php $updated_quote=false; endif; ?>
        <?php if($quote_count==0) : ?> 
			<p>You don't have any quotes currently. Create a new one now!</p>
		<?php else : ?>
			<p>These are your existing quotes. Click on any quote to edit it.</p>
			<table class="table table-striped">
				<thead>
					<tr>
					<th>#</th>
					<th>Company</th>
					<th>Email</th>
					<th>Total</th>
					</tr>
				</thead>
				<tbody>
				<?php 
					foreach($result as $quotedb){
						$quotedb_id = $quotedb['ID'];
						$quotedb_email = $quotedb['Customer_Email'];
						$quotedb_company = $quotedb['Customer_ID'];
						$quotedb_total = $quotedb['Total'];
						$sql = "SELECT name FROM customers WHERE id='$quotedb_company'";
						$sql =  $niu_conn->prepare($sql);
						$sql->execute();
						$companies = $sql->fetch();
						$company_name = $companies[0];
						if ($quotedb['IsFinal']==1) $link = "salesViewQuote.php?id=$quotedb_id";
						else $link = "salesEditQuote.php?id=$quotedb_id";
						
						echo "<tr>
								<th scope='row'><a href='$link'>$quotedb_id</a></th>
								<td>$company_name</td>
								<td>$quotedb_email</td>
								<td>$ $quotedb_total</td>
							  </tr>";
					}
				?>
			  </tbody>
			</table>
		<?php endif; ?>	
        <p>
          <a class="btn btn-lg btn-primary" href="salesCreateQuote.php" role="button">Create new quote &raquo;</a>
        </p>
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
