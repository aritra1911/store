<html>
<head>
    <link rel="stylesheet" href="prod_mast.css" />
</head>
<body>
<?php
    // $serverName = "localhost";
    // $userName = "postgres";
    // $password = "unlockdb";
    // $dbName = "store";

    $id = $_GET["id"];
    $new_id = $id; // Will need to redirect to another product after deletion
    $edit = $_GET["edit"];

    // Create connection
    $conn = pg_connect("
        host=localhost
        dbname=store
        user=postgres
        password=unlockdb
    ");

    // Check connection
    if (!$conn)
        die("Error in connection: " . pg_last_error());

    ?>
    <!-- Header -->
    <div class="header">
        PRODUCT MASTER
    </div>
    <?php

    // Message Box
    echo "<div>";

    // Update if edit submission is pending
    if (isset($_POST['code']) && isset($_POST['name']) && isset($_POST['code'])) {
        $code = $_POST['code'];
        $name = $_POST['name'];
        $packing = $_POST['packing'];

        // Renew
        if (pg_query($conn, "UPDATE products SET prodName='" . $name . "', packing='" . $packing ."' WHERE prodCode='" . $code . "'")) {
            echo "<font color=\"#008000\">\n";
            echo "<i>Product <b>" . $name . "</b> with code <b>" . $code . "</b> has been successfully updated.</i>\n";
            echo "</font><br />";
        } else {
            echo "<font color=\"#b22222\">\n";
            echo "<i><b>Error updating product:</b></i><br />" . $query . "<br />" . pg_last_error();
            echo "</font><br />\n";
        }
    }

    // What's worth waiting? Put the bullet in its head.
    if (isset($_POST['id'])) {
        $query = "DELETE FROM products WHERE prodCode='" . $_POST['id'] . "'";
        if ($result = pg_query($conn, $query)) {
            echo '<font color="#b22222"><i><b>';
            echo pg_affected_rows($result) . '</b> row(s) of data successfully deleted.</i></b>';
            echo '</i></font><br />';
        } else {
            echo '<font color="#b22222">';
            echo "Error: " . $query . "<br />" . pg_last_error();
            echo '</font><br />';
        }
    }

    echo "</div>";

    $queryCurrent = "SELECT * FROM products WHERE prodCode=" . $id;
    $result = pg_query($conn, $queryCurrent);

    if ($row = pg_fetch_array($result)) {
        if ($edit == 0) { // The Readonly Section
            // tables keep everything tidy in a grid but we don't like borders around it
            echo '<table class"current" cellspacing="7">';
            echo '<tr><th class="current">Product Code :</th>';
            echo '<td class="current">' . $row[0] . '</td></tr>';
            echo '<tr><th class="current">Product Name :</th>';
            echo '<td class="current">' . $row[1] . '</td></tr>';
            echo '<tr><th class="current">Packing :</th>';
            echo '<td class="current">' . $row[2] . '</td></tr>';
            echo '</table><br />';
            echo '<table cellspacing="7"><tr>';
            echo '<td><a class="button" href="prod_mast.php?id=' . $id . '&edit=1">EDIT</a></td>';
            echo '<td><a class="button" href="prod_mast.php?id=' . $id . '&edit=-1">DELETE</a></td>';
            echo '</tr></table><br /><br />';

        } else if ($edit == 1) { // The Alter Section
            ?>
            <form action = "<?php echo $_SERVER['PHP_SELF']."?id=".$id."&edit=0"; ?>" method = "post">
                <table>
                    <tr>
                        <td>Code:</td>
                        <td><input type="text" name="code" maxlength="5" size="3" value="<?php echo $row[0]; ?>" readonly /></td>
                    </tr>
                    <tr>
                        <td>Name:</td>
                        <td><input type="text" name="name" maxlength="25" size="20" value="<?php echo $row[1]; ?>" /></td>
                    </tr>
                    <tr>
                        <td>Packing:</td>
                        <td><input type="text" name="packing" maxlength="10" value="<?php echo $row[2]; ?>" /></td>
                    </tr>
                    <tr>
                        <td><input type="submit" name="submit" value="UPDATE" /></td>
                        <td><a class="button" href="<?php echo 'prod_mast.php?id=' . $id . '&edit=0'; ?>">CANCEL</a></td>
                    </tr>
                </table>
            </form>
            <?php

        } else { // The Good Bye Section
            $result = pg_query($conn, "SELECT * FROM products WHERE prodCode >= '" . $id . "' LIMIT 2");
            $row = pg_fetch_array($result);

            // Time to move on...
            if ($row = pg_fetch_array($result))
                $new_id = $row[0]; // get the next one
            else {
                $result = pg_query($conn, "SELECT * FROM products ORDER BY prodCode");
                if ($row = pg_fetch_array($result)) $new_id = $row[0];
                else $new_id = -1;
            }

            echo "Are you sure?<br />"
            ?>
            <form action = "<?php echo $_SERVER['PHP_SELF']."?id=".$new_id."&edit=0"; ?>" method = "post">
                <input type="hidden" name="id" value="<?php echo $id ?>" />
                <input type="submit" name="submit" value="Yes" />
                <a class="button" href="<?php echo 'prod_mast.php?id=' . $id . '&edit=0'; ?>">Cancel</a>
            </form>
            <?php
        } // End of sections, create some more if you don't consider them useless.
    }

    if ($edit != -1) { // I hate to show this while the funeral procession
        $queryAll = "SELECT * FROM products ORDER BY prodName";
        $result = pg_query($conn, $queryAll);

        if ($result) {
            echo '<table class="view" cellpadding="2">';
            echo '<tr><td class="viewHead">Code</td>';
            echo '<td class="viewHead">Product Name</td>';
            echo '<td class="viewHead">Packing</td>';
            echo '<td class="viewHead"></td></tr>';
            // output data of each row
            while($row = pg_fetch_array($result)) {
                echo '<tr>';
                echo '<td class="viewBody">' . $row[0] . "</td>";
                echo '<td class="viewBody">' . $row[1] . "</td>";
                echo '<td class="viewBody">' . $row[2] . "</td>";
                echo '<td align="center">';
                echo '<a class="button" href="prod_mast.php?id=' . $row[0] . '&edit=0">SELECT</a></td></tr>';
            }
            echo "</table>";
        } else
            echo "Nothing to see here. Go try adding a few products first.<br />";
        echo "<br />"; // Take a break
        echo '<a href="index.php">&lt;&lt; Back</a>';
        ?>
        <br />
        <br />
        <div>
            <i class="arrow"></i>
        </div>
        <?php
    }
    pg_free_result($result);

    // Finish it
    pg_close($conn);
?>

</body>
</html>
