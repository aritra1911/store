<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <link rel="stylesheet" href="prod_add.css" />
</head>
</head>
<body>
    <?php
        $sv = "localhost";
        $un = "root";
        $pass = "unlockdb";
        $db = "storedb";

        $conn = new mysqli($sv, $un, $pass, $db); // Establish a conne-ction

        // Check connection
        if ($conn->connect_error)
            die("Connection failed: " . $conn->connect_error);

        if (isset($_POST['code']) && isset($_POST['name']) && isset($_POST['code'])) {
            $code = $_POST['code'];
            $name = $_POST['name'];
            $packing = $_POST['packing'];

            $query = "INSERT INTO products (prodCode, prodName, packing)
                VALUES ('" . $_POST['code'] . "', '" . $_POST['name'] . "', '" . $_POST['packing'] . "');";

            // Try adding the data
            if ($conn->query($query) === TRUE) {
                echo "<font color=\"#008000\">\n";
                echo "<i>Product <b>" . $name . "</b> with code <b>" . $code . "</b> has been successfully added.</i>\n";
                echo "</font><br />";
            } else {
                echo "<font color=\"#b22222\">\n";
                echo "<i><b>Error adding product:</b></i><br />" . $query . "<br />" . $conn->error;
                echo "</font><br />\n";
            }
        }
    ?>
    <form action = "<?php echo $_SERVER['PHP_SELF']; ?>" method = "post">
        <table>
            <tr>
                <td>Code:</td>
                <?php
                    // Get the last product code
                    $result = $conn->query("SELECT * FROM products ORDER BY prodCode DESC");
                    $id = -1;
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $id = $row['prodCode'];
                    }

                    // Suggest a suitable product code
                    if ($id == -1)
                        echo '<td><input type="text" name="code" maxlength="5" size="3" value="1" /></td>';
                    else
                        echo '<td><input type="text" name="code" maxlength="5" size="3" value="' . ($id + 1) . '" /></td>';                    
                ?>
            </tr>
            <tr>
                <td>Name:</td>
                <td><input type="text" name="name" maxlength="25" size="20" /></td>
            </tr>
            <tr>
                <td>Packing:</td>
                <td><input type="text" name="packing" maxlength="10" /></td>
            </tr>
            <tr>
                <td><input id="add" type="submit" name="submit" value="Submit" /></td>
                <td><a href="index.php">You may even wanna take a step back</a></td>
            </tr>
        </table>
    </form>
</body>
</html>