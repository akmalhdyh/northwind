<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipper</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>


    <?php
    $con = mysqli_connect("localhost", "root", "", "northwind");
    //===============================================================================================================================

    if (!isset($_GET["status"])) $_GET["status"] = "list_shippers";
    if (!isset($_POST["status"])) $_POST["status"] = "list_shippers";

    if ($_POST["status"] == "list_shippers_save_edit") list_shippers_save_edit();
    elseif ($_GET["status"] == "list_shippers_save_delete") list_shippers_save_delete();
    elseif ($_GET["status"] == "list_shippers") list_shippers();
    elseif ($_GET["status"] == "list_shippers_new") list_shippers_new();
    elseif ($_GET["status"] == "list_shippers_new_save") list_shippers_new_save();
    elseif ($_GET["status"] == "list_shippers_edit") list_shippers_edit();
    elseif ($_GET["status"] == "list_shippers_order") list_shippers_order();

    //===============================================================================================================================
    function list_shippers()
    {

        error_reporting(E_ALL ^ E_NOTICE);
        $q = mysqli_query($GLOBALS["con"], "SELECT * FROM shippers");

        $no = 0;
        $list_shippers = "";
        while ($h = mysqli_fetch_array($q)) {
            $list_shippers = $list_shippers .
                "<tr>
                <td>" . ++$no . "</td>
                <td>$h[shippername]</td>
                <td>$h[phone]</td>
                <td>
                    <a href='list_shippers.php?status=list_shippers_edit&shipperid=$h[shipperid]' class='btn btn-success btn-sm'>Edit</a> 
                    <a href='list_shippers.php?status=list_shippers_save_delete&shipperid=$h[shipperid]' class='btn btn-danger btn-sm'>Del</a>
                    <a href='list_shippers.php?status=list_shippers_order&shipperid=$h[shipperid]' class='btn btn-primary btn-sm'>Orders</a>
                </td>
            </tr>";
        }
        $list_shippers =
            "<div class='container mt-3'>
     <h2>List Shippers</h2>
     <a href='index.php' class='btn btn-warning'>< Back</a><br><br>
        <p><a href='list_shippers.php?status=list_shippers_new' class='btn btn-info btn-sm'>Add New</a></p>
        <table class='table table-striped'>
        <tr class='table-success'>
                        <th>No</th>
                        <th>Shipper Name</th>
                        <th>Phone</th>
                        <th><center>Action</center></th>
                    </tr>
                    $list_shippers
                </table>
            </div>";

        mysqli_close($GLOBALS["con"]);
        echo $list_shippers;
    }
    //===============================================================================================================================
    function list_shippers_new()
    {
        $formAddNew =
            "<div class='container mt-3'>
        <h2>NEW Shippers</h2>
        <br>
        <a href='list_shippers.php' class='btn btn-warning'>< Back</a><br><br>
        <form action='list_shippers.php?status=list_shippers_new_save' method='post'>
        <table>
        <tr>
            <td>Shipper Name</td>
            <td><input type='text' class='form-control' name='shippername'></td>
        </tr>
        <tr>
            <td>Phone</td>
            <td><input type='text' class='form-control' name='phone'></td>
        </tr>
        </table>
        <br>
            <button type='submit' class='btn btn-success'>Submit</button> 
            <a href='' class='btn btn-danger'>Clear</a>
            </form>
        </div>";
        echo $formAddNew;
    }
    //===============================================================================================================================
    function list_shippers_new_save()
    {
        $q = mysqli_query($GLOBALS["con"], "INSERT INTO shippers(shippername, phone) VALUES ('$_POST[shippername]',
    '$_POST[phone]')");
        mysqli_close($GLOBALS["con"]);

        header('Location: list_shippers.php');
    }

    //===============================================================================================================================
    function list_shippers_edit()
    {
        $q = mysqli_query($GLOBALS["con"], "SELECT * FROM shippers WHERE shipperid='$_GET[shipperid]'");
        while ($h = mysqli_fetch_array($q)) {
            $shipperid = $h["shipperid"];
            $shippername = $h["shippername"];
            $phone = $h["phone"];
        }
        if ($_GET["status"] == "clear") {
            $shipperid = $shippername = $phone = "";
        }
        mysqli_close($GLOBALS["con"]);

        $formAddEdit =
            "<div class='container mt-3'>
        <h2>EDIT Shippers</h2><br>
        <a href='list_shippers.php' class='btn btn-warning'>< Back</a><br><br>
        <form action='list_shippers.php?status=list_shippers_save_edit' method='post'>
        <input type='hidden' name='shipperid' value='$shipperid'/>
        <table>
        <tr>
            <td>Shipper Name</td>
           <td><input type='text' class='form-control' value='$shippername' name='shippername'></td>
        </tr>
        <tr>
            <td>Phone</td>
           <td><input type='text' class='form-control' value='$phone' name='phone'></td>
        </tr>
        </table>
        <br>
            <input type='hidden' name='status' value='list_shippers_save_edit'>
			<button type='submit' class='btn btn-success'>Submit</button>
            <a href='list_shippers.php?status=clear&shipperid=$shipperid' class='btn btn-danger'>Clear</a> 
        </form>
    </div>";

        echo $formAddEdit;
    }
    //===============================================================================================================================
    function list_shippers_save_edit()
    {
        error_reporting(E_ALL ^ E_NOTICE);

        $q = mysqli_query(
            $GLOBALS["con"],
            "UPDATE shippers SET
			shipperid='$_POST[shipperid]',
			shippername='$_POST[shippername]',
			phone='$_POST[phone]'
		WHERE shipperid='$_POST[shipperid]'"
        );

        mysqli_close($GLOBALS["con"]);

        header('Location: list_shippers.php');
    }
    //===============================================================================================================================
    function list_shippers_save_delete()
    {
        $q = mysqli_query($GLOBALS["con"], "DELETE FROM shippers WHERE shipperid='$_GET[shipperid]'");
        header('Location: list_shippers.php');
    }
    //===============================================================================================================================
    function list_shippers_order()
    {
        $q = mysqli_query(
            $GLOBALS["con"],
            "SELECT *,customername,contactname,firstname,lastname FROM orders 
    JOIN customers ON orders.customerid=customers.customerid 
    JOIN employees ON orders.employeeid=employees.employeeid 
    WHERE shipperid='$_GET[shipperid]' ORDER BY orderdate"
        );

        $orders = "";
        $index = 0;
        $grandtotal = 0;
        while ($h = mysqli_fetch_array($q)) {
            $total = 0;
            $q1 = mysqli_query(
                $GLOBALS["con"],
                "SELECT SUM(quantity*price) AS total
        FROM products INNER JOIN (Orders INNER JOIN OrderDetails 
        ON Orders.OrderID=OrderDetails.OrderID) ON Products.ProductID=OrderDetails.ProductID
        GROUP BY Orders.OrderID
        HAVING Orders.OrderID='$h[orderid]'"
            );

            while ($h1 = mysqli_fetch_array($q1)) {
                $total = $h1["total"];
                $grandtotal = $grandtotal + $total;
            }

            $orders = $orders .
                "<tr id='orders_$index' data-id='$h[orderid]' onclick=f_rowclick(this.id)>
				<td style='text-align:center'>" . ++$index . "</td>
				<td>$h[customername]<br> ($h[contactname])</td>
                <td>$h[firstname] $h[lastname]</td>
				<td>$h[orderdate]</td>
				<td style='text-align:right'>$" . number_format($total, 2) . "</td>
				<td><a href='list_shippers.php?status=list_shippers_order_detail&shipperid=$h[shipperid]' class='btn btn-primary btn-sm'>Details</a></td>
			</tr>";
        }
        mysqli_close($GLOBALS["con"]);

        echo

        "<div class='container mt-3'>
		<h1>LIST ORDER</h1>
		<a href='list_shippers.php' class='btn btn-warning'>< Back</a><br><br>
                <table class='table table-striped' id='orders' >
                    <tr class='table-success'>
						<th>No</th>
						<th>Customer <br> (Contact Name)</th>
						<th>Employee</th>
						<th>Order Date</th>
						<th>Total</th>
						<th>Action</th>
                    </tr>
                    $orders
					<tr>
						<td style='text-align:right;font-weight:bold' colspan=5>
							Grand Total&nbsp;&nbsp;$" . number_format($grandtotal, 2) . "
						</td>
					</tr>
                </table>
            </div>";
    }
    //==========================================================================================================================	

    ?>

</body>

</html>