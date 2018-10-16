<?php
require '../php/session.php';
require '../php/db.php';
require '../php/AdminController.php';

checkCredentials();

//Add associate
if(isset($_POST['createEmployee'])){
	$first_name = $_POST['employeeFirstName'];
	$last_name = $_POST['employeeLastName'];
	$username = $_POST['employeeUsername'];
	$password = $_POST['employeePassword'];
	$address = $_POST['employeeAddress'];
	
	addSalesAssociate($first_name,$last_name,$username,$password,$address,$conn);
	$created_employee=true;
}
//delete employee
if(isset($_GET['id']) && isset($_GET['delete'])){
	$employee_id=$_GET['id'];
	deleteSalesAssociate($employee_id,$conn);
	$deleted_employee=true;
}
//update employee
if(isset($_POST['updateEmployee'])){
	$employee_id=$_POST['employeeID'];
	$first_name = $_POST['employeeFirstName'];
	$last_name = $_POST['employeeLastName'];
	$username = $_POST['employeeUsername'];
	$password = $_POST['employeePassword'];
	$address = $_POST['employeeAddress'];
	$commission = $_POST['employeeCommission'];
	
	updateSalesAssociate($first_name,$last_name,$username,$password,$address,$commission,$employee_id,$conn);
	$updated_employee = true;
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
        <h1>Employees</h1>
		<?php if(isset($deleted_employee)) : ?>
			<p class="alert alert-warning">Employee was successfully deleted</p>
		<?php $deleted_employee=false; endif; ?>
		<?php if(isset($created_employee)) : ?>
			<p class="alert alert-success">Employee was successfully created</p>
		<?php $created_employee=false; endif; ?>
		<?php if(isset($updated_employee)) : ?>
			<p class="alert alert-success">Employee was successfully updated</p>
		<?php $updated_employee=false; endif; ?>
        <?php
			$sql = "SELECT * FROM employee WHERE `Is_Admin`!='1'";
			$sql =  $conn->prepare($sql);
			$sql->execute();
			$employees = $sql->fetchAll();
		?>
			<p>Click on any record to edit it</p>
			<table class="table table-striped">
				<thead>
					<tr>
					<th>#</th>
					<th>Name</th>
					<th>Username</th>
					<th>Accumulated Commission</th>
					<th>Address</th>
					<th>Delete</th>
					</tr>
				</thead>
				<tbody>
				<?php 
					foreach($employees as $employee){
						$employee_id = $employee['ID'];
						$employee_name = $employee['First_Name']." ".$employee['Last_Name'];
						$employee_username = $employee['Username'];
						$employee_commission = number_format(floatval($employee['Commission']), 2, '.', '');
						$employee_address = $employee['Address'];
						
						$link = "adminEditEmployee.php?id=$employee_id";
						
						echo "<tr>
								<th scope='row'><a href='$link'>$employee_id</a></th>
								<td>$employee_name</td>
								<td>$employee_username</td>
								<td>$employee_commission</td>
								<td>$employee_address</td>
								<td><a href='./adminManageEmployees.php?id=$employee_id&delete=1'><img src='../img/remove.png' width='10%'/></a></td>
							  </tr>";
					}
				?>
			  </tbody>
			</table>
			<p>
          <a class="btn btn-lg btn-primary" href="adminCreateEmployee.php" role="button">Create new employee &raquo;</a>
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
