<?php
    $conn = new mysqli("localhost", "root", "unlockdb", "storedb");
    if ($conn->connect_error)
        die("Connection failed!" . $conn->connect_error);
    $sql = "SELECT * FROM products ORDER BY prodCode LIMIT 1";
    $result = $conn->query($sql);
    $id = -1; // Assuming a default bad value
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id = $row["prodCode"];
    }
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
            echo '<li><a href="#">Products Master</a><br />Link unavailable!</li>';
    ?>
    <li><a href="prod_add.html">Add Product(s)</a></li>
</ul>
</body>
</html>