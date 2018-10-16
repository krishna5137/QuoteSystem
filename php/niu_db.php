<?php
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