<?php
    $conn = pg_connect("host=localhost dbname=store user=postgres password=unlockdb");
    if (!$conn)
        die("Error in connection: " . pg_last_error());
    $sql = "SELECT * FROM products ORDER BY prodCode LIMIT 1";
    $result = ph_query($conn, $sql);
    $id = -1; // Assuming a default bad value
    if ($row = pg_fetch_array($result))
        $id = $row["prodCode"];
?>
<html>
<head>
    <title>Welcome to Store</title>
</head>
<body>
<h2>This is a website still under development.<br /></h2>
<h4>Thanks for your visit, interface's gonna get updated <font color="#FF0000"><big>real soon</big></font>.</h4>
<h6>For now, follow the links below to get to your desired location.</h6>
<ul>
    <?php
        if ($id > -1)
            echo '<li><a href="prod_mast.php' . "?id=" . $id . '&edit=0">Products Master</a></li>';
        else
            echo '<li><a href="#">Products Master</a>&nbsp;(No data!)</li>'; // Relax! It does absolutely nothing.
    ?>
    <li><a href="prod_add.php">Add Product(s)</a></li>
</ul>
<?php
    pg_free_result($result);  // free memory
    pg_close($conn);  // close connection
?>
</body>
</html>
