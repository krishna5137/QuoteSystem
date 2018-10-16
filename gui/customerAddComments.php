<?php
require '../php/db.php';
require '../php/niu_db.php';

	//decrypt quote id
	$quote_id = $_GET['id'];
	$quote_id-=3;
	$quote_id/=7;
	
	//update notes
	if(isset($_POST['customerNotes'])){
		$notes=$_POST['customerNotes'];
		$sql = "UPDATE `quote` SET `Customer_Notes`='$notes' WHERE `ID`='$quote_id'";
		$sql =  $conn->prepare($sql);
		$sql->execute();
		$notes_updated = true;
	}
	
	$sql = "SELECT * FROM quote where ID='$quote_id'";
	$sql =  $conn->prepare($sql);
	$sql->execute();
	$quote = $sql->fetch();
	$company_id = $quote['Customer_ID'];
	
	//get companies
	$sql = "SELECT * FROM customers WHERE `ID`='$company_id'";
	$sql =  $niu_conn->prepare($sql);
	$sql->execute();
	$company = $sql->fetch();
	
	
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

    <title>Customer Comments</title>

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
          <a class="navbar-brand" href="#">Quote System</a>
        </div>
      </div>
    </nav>


    <div class="container">

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
		<h2>Your Quote</h2><br />
		<?php if(isset($notes_updated)) : ?>
			<p class="alert alert-success">Your notes were added to this quote and will be reviewed soon.</p>
		<?php $notes_updated=false; endif; ?>
		<form action="" method="post">
			<input type="hidden" name="updateQuote"/>
			<div class="form-group row">
				<label class="col-xs-2 col-form-label">Customer</label>
				<div class="col-xs-10">
					<input class="form-control" type=text" readonly value="<?php echo $company[1];?>" />
				</div>				
			</div>
			<div class="form-group row">
				<label for="quoteEmail" class="col-xs-2 col-form-label">Email address</label>
				<div class="col-xs-10">
					<input type="email" class="form-control" name="quoteEmail" placeholder="Enter Customer email" readonly value="<?php echo $quote['Customer_Email']; ?>">
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
						<input class='form-control' style= 'display:inline; width: 70%;' type='text' name='lineItemDescription[]' value='$line_item' readonly />
						<input class='form-control' style= 'display:inline; width: 20%;' type='text' name='lineItemPrice[]' value='$line_value' required='yes' readonly/>
						</div>";
						}
					?>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-xs-2 col-form-label">Discount</label>
				<div class="col-xs-10">
					<input type="text" class="form-control" name="discountAmount" id="discountAmount" readonly value="<?php echo $quote['Discount'];?>">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-xs-2 col-form-label">Discount Type</label>
				<div class="col-xs-10">
					<select class="form-control" name="discountType" id="discountType" readonly>
						<?php if($quote['Discount_Type']==0) :?>
						<option value="0" selected>Amount</option>
						<option value="1">Percent</option>
						<?php else :?>
						<option value="0">Amount</option>
						<option value="1" selected>Percent</option>
						<?php endif;?>
					</select>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-xs-2 col-form-label">Total Price</label>
				<div class="col-xs-10">
					<p id="total">$ <?php echo $quote['Total'];?></p>
					<input type="hidden" id="total2" name="total" value="<?php echo $quote['Total'];?>" />
					<input type="hidden" id="quote_id" name="quote_id" value="<?php echo $quote['ID'];?>" />
				</div>
			</div>
			<div class="form-group row">
				<label class="col-xs-2 col-form-label">Your Notes</label>
				<div class="col-xs-10">
					<textarea class="form-control" name="customerNotes" rows="3"><?php echo $quote['Customer_Notes']; ?></textarea>
				</div>
			</div>
			<br />
			<input class="btn btn-lg btn-primary" type="submit" value="Update Comments" />
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
