<!DOCTYPE html>
<html>
<head>
    <title>Record Deletion</title>
</head>
<body style="font-family: Verdana, sans-serif;">

<?php
    $id = $_GET['id'];

    $sv = "localhost";
    $un = "root";
    $pass = "unlockdb";
    $db = "storedb";

    $conn = new mysqli($sv, $un, $pass, $db); // Establish a conne-ction

    // Check connection
    if ($conn->connect_error)
        die("Connection failed: " . $conn->connect_error);

    $query = "DELETE FROM products WHERE prodCode='" . $id . "'"; // The KILL statement

    $result = $conn->query("SELECT * FROM products WHERE prodCode >= '" . $id . "' LIMIT 2");
    $row = $result->fetch_assoc();
    $code = $row['prodCode'];
    $name = $row['prodName'];
    $packing = $row['packing'];

    // Move to another record
    if ($row = $result->fetch_assoc())
        $id = $row['prodCode'];
    else {
        $result = $conn->query("SELECT * FROM products ORDER BY prodCode");
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id = $row['prodCode'];
        } else $id = -1;

    }

    // Some info about what's going on
    echo '<div style="font-family: Consolas, monospace;">';
    echo 'Code : ' . $code . '<br />';
    echo 'Product Name : ' . $name . '<br />';
    echo 'Packing: ' . $packing . '<br />';
    echo 'Action : Delete<br />';
    echo '</div>';

    // Say goodbye to the data
    if ($conn->query($query) === TRUE) {
        echo '<font color="#b22222">';
        echo mysqli_affected_rows($conn) . ' row(s) of data successfully deleted.';
        echo '</font><br />';
    } else
        echo "Error: " . $query . "<br>" . $conn->error;

    $conn->close(); // The End

    if ($id != -1) // Looks like an error, an empty table.
        echo '<a href="prod_mast.php?id=' . $id . '&edit=0">Back to master</a>';
    else
        echo '<a href="prod_add.html">Add a product</a>';
?>

</body>
</html>