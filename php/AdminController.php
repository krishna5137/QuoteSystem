<?php

function addSalesAssociate($first_name,$last_name,$username,$password,$address,$conn){
	$sql = "INSERT INTO `employee`(`First_Name`, `Last_Name`, `Username`, `Password`, `Address`) VALUES ('$first_name','$last_name','$username','$password','$address')";
	$sql =  $conn->prepare($sql);
	$sql->execute();
	return;
}

function deleteSalesAssociate($id,$conn){
	$sql = "DELETE FROM employee where ID='$id'";
	$sql =  $conn->prepare($sql);
	$sql->execute();
	return;
}

function updateSalesAssociate($first_name,$last_name,$username,$password,$address,$commission,$employee_id,$conn){
	$sql = "UPDATE `employee` SET `First_Name`='$first_name',`Last_Name`='$last_name',`Username`='$username',`Password`='$password',`Commission`='$commission',`Address`='$address' WHERE `ID`='$employee_id'";
	$sql =  $conn->prepare($sql);
	$sql->execute();
	return;
}

function checkCredentials(){
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
return;}

?>