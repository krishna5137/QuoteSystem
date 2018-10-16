<?php
require '../php/session.php';
require '../php/db.php';
require '../php/niu_db.php';
require '../php/PHPMailerAutoload.php';
require '../php/QuoteController.php';
require '../php/PurchaseOrderController.php';
require '../php/AdminController.php';

checkCredentials();

//Update Quote
if(isset($_POST['updateQuote'])){
	$quote_id = $_POST['quote_id'];
		$updated_quote = true;
		$company_id = $_POST['companyID'];
		
		$sql = "SELECT * FROM customers WHERE ID='$company_id'";
		$sql =  $niu_conn->prepare($sql);
		$sql->execute();
		$company = $sql->fetch();
	
		$company_name = $company[1];
		$quote_email = $_POST['quoteEmail'];
		$secret_notes = $_POST['secretNotes'];
		$is_sanctioned = false;
		if(isset($_POST['isSanctioned'])) $is_sanctioned = true;
		$total = $_POST['total'];
		$line_item = $_POST['lineItemDescription'];
		$line_price = $_POST['lineItemPrice'];
		$total = $_POST['total'];
		$discountAmount = $_POST['discountAmount'];
		$discountType = $_POST['discountType'];
		
		$sql = "UPDATE `quote` SET `Customer_Email`='$quote_email',`Customer_ID`='$company_id',`Sanctioned`='$is_sanctioned',`Notes`='$secret_notes',`Total`='$total',`Discount`='$discountAmount',`Discount_Type`='$discountType',`Last_Updated`=now() WHERE `ID`=$quote_id";
		$sql =  $conn->prepare($sql);
		$sql->execute();
		
		$sql="DELETE FROM `line_item` WHERE `Quote_ID`='$quote_id'";
		$sql =  $conn->prepare($sql);
		$sql->execute();
		
		foreach( $line_item as $key => $line ) {
			$price = $line_price[$key];
			$sql = "INSERT INTO `line_item`(`Description`, `Price`, `Quote_ID`) VALUES ('$line','$price','$quote_id')";
			$sql =  $conn->prepare($sql);
			$sql->execute();
		}
		
	//send email for sanction quote
	if($is_sanctioned){
		$mail = new PHPMailer;
		$mail->isSMTP();
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 587;
		$mail->SMTPDebug = 0;
		$mail->SMTPSecure = 'tls';
		$mail->SMTPAuth = true;
		$mail->SMTPOptions = array(
			'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
		));
		$mail->Username = "gradgroup05@gmail.com";
		$mail->Password = "temporarypassword";
		$mail->setFrom('gradgroup05@gmail.com', 'Grad Group5');
		$mail->addAddress($quote_email, '');
		$mail->Subject = 'Your quote from Grad Group5';
		$msgString = "<p>Hi $company_name,</p><br />";
		$msgString .= "<p>This is your quote from Grad Group5</p><br /><table><tr><th>Item</th><th>Price</th></tr>";
		$sql = "SELECT * FROM `line_item` WHERE `Quote_ID`='$quote_id'";
		$sql =  $conn->prepare($sql);
		$sql->execute();
		$lines = $sql->fetchAll();
		foreach($lines as $line){
			$msgString .= "<tr><td>".$line['Description']."</td><td>$ ".$line['Price']."</td></tr>";
		}
		$msgString .= "</table><br />";
		if($discountType==0) $discountString = "$ $discountAmount";
		else $discountString = "$discountAmount %";
		$msgString .= "<p>Discount: $discountString</p>";
		$msgString .= "<p>Total: $ $total</p><br />";
		$temp_quote = ($quote_id*7)+3;
		$msgString .= "<p>You can add comments to your quote <a href='http://localhost/quote/gui/customerAddComments.php?id=$temp_quote'>here</a></p>";
		$msgString .= "<p>Have a good day!</p>";
		$mail->msgHTML($msgString);
		$mail->send();
	}
	
	$updated_quote=true;
}

if(isset($_POST['createPO'])){
	$quote_id = $_POST['quote_id'];
	$company_po = $_POST['companyName'];
	$total_po = $_POST['total'];
	
	//Get PO number and Commission rate
	$random = 0;
	$result = computeCommissionRate($conn,$company_po,$total_po,$random);
	$result = explode("processed on ", $result);
	$result2=explode(",", $result[1]);
	$date=$result2[0];
	$date=date("Y-m-d",strtotime($date));
	$commission = explode(": ", $result2[1]);
	$commission = floatval($commission[1]);
	$total=$_POST['total'];
	$quote_email=$_POST['quoteEmail'];
	$company_name = $_POST['companyName'];
	
	//Send Email
		$mail = new PHPMailer;
		$mail->isSMTP();
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 587;
		$mail->SMTPDebug = 0;
		$mail->SMTPSecure = 'tls';
		$mail->SMTPAuth = true;
				$mail->SMTPOptions = array(
			'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
		));
		$mail->Username = "gradgroup05@gmail.com";
		$mail->Password = "temporarypassword";
		$mail->setFrom('gradgroup05@gmail.com', 'Grad Group5');
		$mail->addAddress($quote_email, '');
		$mail->Subject = 'Your Purchase Order from Grad Group5';
		$msgString = "<p>Hi $company_name,</p><br />";
		$msgString .= "<p>This is your Purchase Order from Grad Group5</p><br /><table><tr><th>Item</th><th>Price</th></tr>";
		$sql = "SELECT * FROM `line_item` WHERE `Quote_ID`='$quote_id'";
		$sql =  $conn->prepare($sql);
		$sql->execute();
		$lines = $sql->fetchAll();
		foreach($lines as $line){
			$msgString .= "<tr><td>".$line['Description']."</td><td>$ ".$line['Price']."</td></tr>";
		}
		$msgString .= "</table><br />";
		$msgString .= "<p>Total price after discounts: $ $total</p><br />";
		$msgString .= "<p>Your order will be processed on $date</p>";
		$msgString .= "<p>Have a good day!</p>";
		$mail->msgHTML($msgString);
		$mail->send();
	
	$discount = floatval($_POST['finalDiscountAmount']);
	$employee_id=$_POST['employeeID'];
	$discountType=$_POST['finalDiscountType'];
	processPurchaseOrder($conn,$quote_id,$discount,$employee_id,$random,$discountType,$date,$total,$commission);
	
	$created_PO=true;
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
          <a class="navbar-brand" href="#">Admin Interface</a>
		  <ul class="nav navbar-nav">
            <li class="nav-item">
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
        <h1>Quotes</h1>
        <?php
			$result = getQuote("",$conn);
			$quote_count = count($result);
		?>
		<?php if(isset($created_PO)) : ?>
			<p class="alert alert-success">A purchase order was created and sent to the customer</p>
		<?php $created_PO=false; endif; ?>
		<?php if(isset($updated_quote)) : ?>
			<p class="alert alert-success">Your quote was successfully updated</p>
		<?php $updated_quote=false; endif; ?>
		<?php if($quote_count==0) : ?> 
			<p>There are no finalized quotes in the system.</p>
		<?php else : ?>
			<p>These are the finalized quotes in the system. Click on any quote to edit it.</p>
			<table class="table table-striped">
				<thead>
					<tr>
					<th>#</th>
					<th>Company</th>
					<th>Email</th>
					<th>Status</th>
					<th>Employee</th>
					<th>Total</th>
					<th>Last Updated</th>
					</tr>
				</thead>
				<tbody>
				<?php 
					foreach($result as $quotedb){
						$quotedb_id = $quotedb['ID'];
						$quotedb_email = $quotedb['Customer_Email'];
						$quotedb_company = $quotedb['Customer_ID'];
						$quotedb_total = $quotedb['Total'];
						$quotedb_employee = $quotedb['Employee_ID'];
						$sql = "SELECT name FROM customers WHERE id='$quotedb_company'";
						$sql =  $niu_conn->prepare($sql);
						$sql->execute();
						$companies = $sql->fetch();
						$company_name = $companies[0];
						if ($quotedb['Ordered']==1) $status="Ordered";
						elseif ($quotedb['Sanctioned']==1) $status="Sanctioned";
						else $status="Final";
						$last_updated=date('m-d-Y', strtotime($quotedb['Last_Updated']));
						$sql = "SELECT CONCAT (`First_Name`, ' ', `Last_Name`) FROM `employee` WHERE `ID`='$quotedb_employee'";
						$sql =  $conn->prepare($sql);
						$sql->execute();
						$employee_name = $sql->fetch();
						$employee_name = $employee_name[0];
						
						$link="adminEditQuote.php?id=$quotedb_id";
						if ($quotedb['Sanctioned']==1) $link = "adminCreatePO.php?id=$quotedb_id";
						if ($quotedb['Ordered']==1) $link = "adminViewPO.php?id=$quotedb_id";
					
						echo "<tr>
								<th scope='row'><a href='$link'>$quotedb_id</a></th>
								<td>$company_name</td>
								<td>$quotedb_email</td>
								<td>$status</td>
								<td>$employee_name</td>
								<td>$ $quotedb_total</td>
								<td>$last_updated</td>
							  </tr>";
					}
				?>
			  </tbody>
			</table>
		<?php endif; ?>		
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
