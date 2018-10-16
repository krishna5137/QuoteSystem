<?php
function processPurchaseOrder($conn,$quote_id,$discount,$employee_id,$random,$discountType,$date,$total,$commission){
	//Update Quote to say that it was ordered 
	$sql="UPDATE `quote` SET `Ordered`=1 WHERE `ID`='$quote_id'";
	$sql =  $conn->prepare($sql);
	$sql->execute();
	
	//Create PO
	$sql = "INSERT INTO `purchase_order`(`ID`, `Discount`, `Discount_Type`, `Processing_Date`, `Total` ,`Quote_ID`) VALUES ('$random','$discount','$discountType','$date','$total','$quote_id')";
	$sql =  $conn->prepare($sql);
	$sql->execute();
	
	//Update Commission of SalesPerson
	$sql = "SELECT `Commission` FROM employee WHERE `ID`='$employee_id'";
	$sql =  $conn->prepare($sql);
	$sql->execute();
	$prior_commission = $sql->fetch();
	$prior_commission = floatval($prior_commission[0]);
	$commission=$commission*$total/100;
	$final_commission = $prior_commission + $commission;
	$sql="UPDATE `employee` SET `Commission`='$final_commission' WHERE `ID`='$employee_id'";
	$sql =  $conn->prepare($sql);
	$sql->execute();
	return;
}

function viewPurchaseOrder($quote_id,$conn){
	$sql="SELECT * FROM purchase_order WHERE `Quote_ID`='$quote_id'";
				$sql =  $conn->prepare($sql);
				$sql->execute();
				return $sql->fetch();
}

function computeCommissionRate($conn,$company_po,$total_po,&$random){
	$url = 'http://blitz.cs.niu.edu/PurchaseOrder/';
	
	while(true){
		$random = rand(100000,999999);
		$sql="SELECT count(ID) FROM `purchase_order` WHERE `ID`='$random'";
		$sql =  $conn->prepare($sql);
		$sql->execute();
		$count=$sql->fetch();
		if($count[0]!=0) continue;
		
		$data = array('order' => $random, 'name' => $company_po, 'amount' => $total_po);
		$options = array(
			'http' => array(
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'method'  => 'POST',
			'content' => http_build_query($data)
		)
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		If (substr( $result, 0, 5 ) === "Error") continue;
		break;
	}
	return $result;
}
?>