<!DOCTYPE html>
<html>
<head>
	<title>Record Updation</title>
</head>
<body>

<?php
	$id = $_GET['id'];
	$del = $_GET['del'];

	$code = "";
	$name = "";
	$packing = "";
	if ($del == 0) {
		$code = $_POST['code'];
		$name = $_POST['name'];
		$packing = $_POST['packing'];
	}

	$sv = "localhost";
	$un = "root";
	$pass = "unlockdb";
	$db = "storedb";

	$conn = new mysqli($sv, $un, $pass, $db); // Establish a conne-ction

	// Check connection
	if ($conn->connect_error)
		die("Connection failed: " . $conn->connect_error);

	if ($del == 0)
		$query = "UPDATE products SET prodName='" . $name . "', packing='" . $packing ."' WHERE prodCode='" . $id . "'" ;
	else
		$query = "DELETE FROM products WHERE prodCode='" . $id . "'" ;

	// Try adding the data
	if ($conn->query($query) === TRUE)
    	echo "<h1>All good!</h1><br />";
	else
	    echo "Error: " . $query . "<br>" . $conn->error;

	$conn->close();

	echo '<a href="prod_mast.php?id=' . $id . '&edit=0">Back to master</a>';
?>

</body>
</html>