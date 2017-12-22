<html>
<head>
    <link rel="stylesheet" href="prod_mast.css" />
</head>
<body>
    <h1>PRODUCT MASTER</h1>

<?php
    $serverName = "localhost";
    $userName = "root";
    $password = "unlockdb";
    $dbName = "storedb";

    $id = $_GET["id"];
    $edit = $_GET["edit"];

    // Create connection
    $conn = new mysqli($serverName, $userName, $password, $dbName);

    // Check connection
    if ($conn->connect_error)
        die("Connection failed: " . $conn->connect_error);
    //echo "<font color=\"#008000\">Connection successful.</font>";

    $queryCurrent = "SELECT * FROM products WHERE prodCode=" . $id;
    $res = $conn->query($queryCurrent);

    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        if ($edit == 0) {
            // tables keep everything tidy in a grid and we don't want borders
            echo '<table class"current" cellspacing="7">';
            echo '<tr><th class="current">Product Code :</th>';
            echo '<td class="current">' . $row["prodCode"] . '</td></tr>';
            echo '<tr><th class="current">Product Name :</th>';
            echo '<td class="current">' . $row["prodName"] . '</td></tr>';
            echo '<tr><th class="current">Packing :</th>';
            echo '<td class="current">' . $row["packing"] . '</td></tr>';
            echo '</table><br />';
            echo '<a class="button" href="prod_mast.php?id=' . $id . '&edit=1">Edit</a>';
            echo '<a class="button" href="prod_mast_update.php?id=' . $id . '&del=1">Delete</a>';
            echo '<br /><br /><br />';
        } else {
            $url = 'prod_mast_update.php?id=' . $id . '&del=0';
            ?>
            <form action = "<?php echo $url; ?>" method = "post">
                <table>
                    <tr>
                        <td>Code:</td>
                        <td><input type="text" name="code" maxlength="5" size="3" value="<?php echo $row['prodCode']; ?>" readonly /></td>
                    </tr>
                    <tr>
                        <td>Name:</td>
                        <td><input type="text" name="name" maxlength="25" value="<?php echo $row['prodName']; ?>" /></td>
                    </tr>
                    <tr>
                        <td>Packing:</td>
                        <td><input type="text" name="packing" maxlength="10" value="<?php echo $row['packing']; ?>" /></td>
                    </tr>
                    <tr>
                        <td><input type="submit" name="submit" value="Submit" /></td>
                        <td><a class="button" href="<?php echo 'prod_mast.php?id=' . $id . '&edit=0'; ?>">Cancel</a></td>
                    </tr>
                </table>
            </form>
            <?php
        }
    }

    $queryAll = "SELECT * FROM products ORDER BY prodName";
    $result = $conn->query($queryAll);

    if ($result->num_rows > 0) {
        echo '<table class="view" cellpadding="2">';
        echo '<tr><td class="viewHead">Code</td>';
        echo '<td class="viewHead">Product Name</td>';
        echo '<td class="viewHead">Packing</td>';
        echo '<td class="viewHead"></td></tr>';
        // output data of each row
        while($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td class="viewBody">' . $row["prodCode"] . "</td>";
            echo '<td class="viewBody">' . $row["prodName"] . "</td>";
            echo '<td class="viewBody">' . $row["packing"] . "</td>";
            echo '<td align="center" style="font-family: monospace">';
            echo '<a class="button" href="prod_mast.php?id=' . $row["prodCode"] . '&edit=0">Select</a></td></tr>';
        }
        echo "</table>";
    }
    else
        echo "Nothing to see here. Go try adding a few products first.";
    $conn->close();
?>

</body>
</html>