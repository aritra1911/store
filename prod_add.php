<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <link rel="stylesheet" href="prod_add.css" />
</head>
</head>
<body>
    <?php
        // Establish a conne-ction
        $conn = pg_connect("
            host=localhost
            dbname=store
            user=postgres
            password=unlockdb
        ");

        // Check connection
        if (!$conn)
            die("Error in connection: " . pg_last_error());

        // Update when data is posted
        if (isset($_POST['code']) && isset($_POST['name']) && isset($_POST['code'])) {
            $code = $_POST['code'];
            $name = $_POST['name'];
            $packing = $_POST['packing'];

            $query = "INSERT INTO products (prodCode, prodName, packing)
                VALUES ('" . $_POST['code'] . "', '" . $_POST['name'] . "', '" . $_POST['packing'] . "');";

            // Try adding the data
            if (pg_query($conn, $query)) {
                echo "<font color=\"#008000\">\n";
                echo "<i>Product <b>" . $name . "</b> with code <b>" . $code . "</b> has been successfully added.</i>\n";
                echo "</font><br />";
            } else {
                echo "<font color=\"#b22222\">\n";
                echo "<i><b>Error adding product:</b></i><br />" . $query . "<br />" . pg_last_error();
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
                    $result = pg_query($conn, "SELECT * FROM products ORDER BY prodCode DESC");
                    $id = -1;
                    if ($row = pg_fetch_array($result))
                        $id = $row[0];

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
                <td><input id="add" type="submit" name="submit" value="ADD" /></td>
                <td><input id="reset" type="reset" name="reset" value="CLEAR" /></td>
                <!--<td><a class="cbutton" href="index.php">Cancel</a></td>-->
            </tr>
        </table>
    </form>
    <a href="index.php">&lt;&lt; Back</a>
    <?php
        pg_free_result($result);  // free memory
        pg_close($conn);  // close connection
    ?>
</body>
</html>
