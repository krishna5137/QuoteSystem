<?php
//function update given quote
function updateQuote($quote_email,$company_id,$is_final,$secret_notes,$total,$quote_id,$line_item,$line_price,$conn){
	$sql = "UPDATE `quote` SET `Customer_Email`='$quote_email',`Customer_ID`='$company_id',`IsFinal`='$is_final',`Notes`='$secret_notes',`Total`='$total',`Last_Updated`=now() WHERE `ID`=$quote_id";
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
	return;
}

//function to add a new quote
function addQuote($quote_email,$company_id,$is_final,$secret_notes,$id,$total,$line_item,$line_price,$conn){
	$sql = "INSERT INTO `quote`(`Customer_Email`, `Customer_ID`, `IsFinal`, `Notes`, `Employee_ID`, `Total`) VALUES ('$quote_email','$company_id','$is_final','$secret_notes','$id','$total')";
		$sql =  $conn->prepare($sql);
		$sql->execute();
		$quote_id = $conn->lastInsertId();
		
		foreach( $line_item as $key => $line ) {
			$price = $line_price[$key];
			$sql = "INSERT INTO `line_item`(`Description`, `Price`, `Quote_ID`) VALUES ('$line','$price','$quote_id')";
			$sql =  $conn->prepare($sql);
			$sql->execute();
		}
	return;
}

//function to get quotes
function getQuote($id,$conn){
	if($id!="") $sql = "SELECT * FROM quote WHERE Employee_ID='$id'";
	else $sql = "SELECT * FROM quote WHERE `IsFinal`='1'"; ;
	$sql =  $conn->prepare($sql);
	$sql->execute();
	return $sql->fetchAll();
}
?>