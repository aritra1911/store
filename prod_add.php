<?php
	$code = $_POST['code'];
	$name = $_POST['name'];
	$packing = $_POST['packing'];

	$sv = "localhost";
	$un = "root";
	$pass = "unlockdb";
	$db = "storedb";

	$conn = new mysqli($sv, $un, $pass, $db); // Establish a conne-ction

	// Check connection
	if ($conn->connect_error)
		die("Connection failed: " . $conn->connect_error);

	$query = "INSERT INTO products (prodCode, prodName, packing)
		VALUES ('" . $_POST['code'] . "', '" . $_POST['name'] . "', '" . $_POST['packing'] . "');";

	// Try adding the data
	if ($conn->query($query) === TRUE)
    	echo "<h1>All good!</h1><br />";
	else
	    echo "Error: " . $query . "<br>" . $conn->error;

	$conn->close();

	echo "<a href=\"index.html\">Wanna go back?</a>";
?>