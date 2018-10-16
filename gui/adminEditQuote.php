<?php
require '../php/session.php';
require '../php/db.php';
require '../php/niu_db.php';
require '../php/AdminController.php';

checkCredentials();

	$quote_id = $_GET['id'];

	$sql = "SELECT * FROM quote where ID='$quote_id'";
	$sql =  $conn->prepare($sql);
	$sql->execute();
	$quote = $sql->fetch();
	$company_id = $quote['Customer_ID'];
	
	//get companies
	$sql = "SELECT * FROM customers";
	$sql =  $niu_conn->prepare($sql);
	$sql->execute();
	$companies = $sql->fetchAll();
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
		<h2>Edit Quote</h2><br />
		<form action="adminHome.php" method="post">
			<input type="hidden" name="updateQuote"/>
			<div class="form-group row">
				<label class="col-xs-2 col-form-label">Customer</label>
				<div class="col-xs-10">
					<select class="form-control" name="companyID">
					<?php
						foreach($companies as $company){
							if ($company[0]==$quote['Customer_ID']) $selected_string="selected";
							else $selected_string="";
							echo "<option value='".$company[0]."' $selected_string>".$company[1]."</option>";
						}
					?>
					</select>
				</div>				
			</div>
			<div class="form-group row">
				<label for="quoteEmail" class="col-xs-2 col-form-label">Email address</label>
				<div class="col-xs-10">
					<input type="email" class="form-control" name="quoteEmail" placeholder="Enter Customer email" required="yes" value="<?php echo $quote['Customer_Email']; ?>">
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
						$count=0;
						foreach ($lines as $line){
						$line_item = $line[1];
						$line_value = $line[2];
						echo "<div style='margin-bottom: 4px;'>
						<input class='form-control' style= 'display:inline; width: 70%;' type='text' name='lineItemDescription[]' value='$line_item' required='yes'/>
						<input class='form-control' style= 'display:inline; width: 20%;' type='text' name='lineItemPrice[]' value='$line_value' required='yes' onKeyUp='updateTotal();'/><a href='#' onclick='addLine(this); return false;'><img src='../img/add.png' width='3%'/></a>";
						if ($count!=0) echo "<a href='#' onclick='removeLine(this); return false;'><img src='../img/remove.png' width='3%'/></a>";
						echo "</div>";
						$count++;}
					?>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-xs-2 col-form-label">Secret Notes</label>
				<div class="col-xs-10">
					<textarea class="form-control" name="secretNotes" rows="3"><?php echo $quote['Notes']; ?></textarea>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-xs-2 col-form-label">Discount</label>
				<div class="col-xs-10">
					<input type="text" class="form-control" name="discountAmount" id="discountAmount" onkeyup="updateTotal();" value="<?php echo $quote['Discount'];?>">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-xs-2 col-form-label">Discount Type</label>
				<div class="col-xs-10">
					<select class="form-control" name="discountType" id="discountType" onchange="updateTotal();">
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
				<label class="col-xs-2 col-form-label">Sanctioned</label>
				<div class="col-xs-10">
					<input type="checkbox" class="form-check-input" name="isSanctioned">
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
			<br />
			<input class="btn btn-lg btn-primary" type="submit" value="Update quote" />
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
	<script>
		function addLine(element){
			$(element).parent().after("<div style='margin-top: 4px;'><input class='form-control' style= 'display:inline; width: 70%;' type='text' name='lineItemDescription[]' placeholder='Arris SB61 Modem'/><input class='form-control' style= 'display:inline; width: 20%; margin-left: 4px;' type='text' name='lineItemPrice[]' placeholder='70.00' onKeyUp='updateTotal();'/><a href='#' onclick='addLine(this); return false;'><img src='../img/add.png' width='3%' style='margin-left: 4px; margin-right: 4px;'/></a><a href='#' onclick='removeLine(this); return false;'><img src='../img/remove.png' width='3%'/></a></div>");
			return false;
		}
		function removeLine(element){
			$(element).parent().remove();
			updateTotal();
			return false;
		}
		function updateTotal(){
			var total=0.00;
			$('input[name^="lineItemPrice"]').each(function() {
				var temp = parseFloat($(this).val())
				if($.isNumeric( temp )) total+=temp;
			});
			var discountType = $("#discountType").val();
			var discountAmount = parseFloat($("#discountAmount").val());
			if(discountType==0 && $.isNumeric( discountAmount )){
				total-=discountAmount;
			}
			else if($.isNumeric( discountAmount )){
				total=total*(100-discountAmount)/100;
			}
			
			$("#total2").val(total.toFixed(2));
			total = "$ "+total.toFixed(2);
			$("#total").text(total);
			return false;
		}
		
	</script>
  </body>
</html>
