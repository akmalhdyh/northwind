<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>customers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <?php
    $con = mysqli_connect("localhost", "root", "", "northwind");
    //===============================================================================================================================

    if (!isset($_GET["status"])) $_GET["status"] = "list_customers";
    if (!isset($_POST["status"])) $_POST["status"] = "list_customers";

    if ($_POST["status"] == "list_customers_save_edit") list_customers_save_edit();
    elseif ($_GET["status"] == "list_customers_save_delete") list_customers_save_delete();
    elseif ($_GET["status"] == "list_customers") list_customers();
    elseif ($_GET["status"] == "list_customers_new") list_customers_new();
    elseif ($_GET["status"] == "list_customers_new_save") list_customers_new_save();
    elseif ($_GET["status"] == "list_customers_edit") list_customers_edit();
    elseif ($_GET["status"] == "list_customers_order") list_customers_order();
    elseif ($_GET["status"] == "list_customers_order_detail") list_customers_order_detail();
    elseif ($_GET["status"] == "clear") list_customers_edit();



    //===============================================================================================================================
    function list_customers()
    {
        error_reporting(E_ALL ^ E_NOTICE);
        $q = mysqli_query($GLOBALS["con"], "SELECT * FROM customers");

        $no = 0;
        $list_customers = "";
        while ($h = mysqli_fetch_array($q)) {
            $list_customers = $list_customers .
                "<tr>
                    <td>" . ++$no . "</td>
                    <td>$h[customername]<br>($h[contactname])</td>
                    <td>$h[address], $h[city], $h[postalcode], $h[country]</td>
                    <td>
                        <a href='list_customers.php?status=list_customers_edit&customerid=$h[customerid]' class='btn btn-success btn-sm'>Edit</a> 
                        <a href='list_customers.php?status=list_customers_save_delete&customerid=$h[customerid]' class='btn btn-danger btn-sm'>Del</a>
                        <a href='list_customers.php?status=list_customers_order&customerid=$h[customerid]' class='btn btn-primary btn-sm'>Orders</a>
                    </td>
                </tr>";
        }
        $list_customers =
            "
            <div class='container mt-3'>
			<a href='index.php' class='btn btn-warning'>< Back</a><br><br>
                <p><a href='list_customers.php?status=list_customers_new' class='btn btn-info btn-sm'>Add New</a></p>
                <table class='table table-striped'>
                    <tr class='table-success'>
                        <th>No</th>
                        <th>Customer Name <br> (Contact Name)</th>
                        <th>Addres, City, Postal Code, Country</th>
                        <th><center>Action</center></th>
                    </tr>
                    $list_customers 
                </table>
            </div>";

        mysqli_close($GLOBALS["con"]);
        echo $list_customers;
    }
    //===============================================================================================================================
    function list_customers_new()
    {
        $formAddNew =
            "
            <div class='container mt-3'>
                <h2>NEW CUSTOMER</h2><br>
                <a href='list_customers.php' class='btn btn-warning'>< Back</a><br><br>
                <form action='list_customers.php?status=list_customers_new_save' method='post'>
                    <table>
                        <tr>
                            <td>Customer Name </td>
                            <td><input type='text' class='form-control' name='customername'></td>
                        </tr>
                        <tr>
                            <td>Contact Name </td>
                            <td><input type='text' class='form-control' name='contactname'></input></td>
                        </tr>
                        <tr>
                            <td>Address</td></td>
                            <td><input type='text' class='form-control' name='address'></input></td>
                        </tr>
                        <tr>
                            <td>City</td>
                            <td><input type='text' class='form-control' name='city'></input></td>
                        </tr>
                        <tr>
                            <td>Postal Code</td>
                            <td><input type='text' class='form-control' name='postalcode'></input></td>
                        </tr>
                        <tr>
                            <td>Country</td>
                            <td><input type='text' class='form-control' name='country'></input></td>
                        </tr>
                        </table>
                    <br>
                    <button type='submit' class='btn btn-success'>Submit</button> 
                    <a href='new.php' class='btn btn-danger'>Clear</a>
                </form>
            </div>
            ";
        echo $formAddNew;
    }
    //===============================================================================================================================
    function list_customers_new_save()
    {
        $q = mysqli_query($GLOBALS["con"], "INSERT INTO customers(customername, contactname, address, city, postalcode, country) VALUES ('$_POST[customername]',
        '$_POST[contactname]','$_POST[address]','$_POST[city]','$_POST[postalcode]','$_POST[country]')");
        mysqli_close($GLOBALS["con"]);

        header('Location: list_customers.php');
    }
    //===============================================================================================================================
    function list_customers_edit()
    {
        $q = mysqli_query($GLOBALS["con"], "SELECT * FROM customers WHERE customerid='$_GET[customerid]'");
        while ($h = mysqli_fetch_array($q)) {
            $customerid = $h["customerid"];
            $customername = $h["customername"];
            $contactname = $h["contactname"];
            $address = $h["address"];
            $city = $h["city"];
            $postalcode = $h["postalcode"];
            $country = $h["country"];
        }

        if ($_GET["status"] == "clear") {
            $customerid = $customername = $contactname = $address = $city = $postalcode = $country = "";
        }
        mysqli_close($GLOBALS["con"]);

        $formAddEdit =
            "
            <div class='container mt-3'>
                <h2>EDIT CUSTOMER</h2><br>
                <a href='list_customers.php' class='btn btn-warning'>< Back</a><br><br>
                <form action='list_customers.php?status=list_customers_save_edit' method='post'>
				<input type='hidden' name='customerid' value='$customerid'/>
                    <table>
                        <tr>
                            <td>Customer Name </td>
                            <td><input type='text' class='form-control' value='$customername' name='customername'></td>
                        </tr>
                        <tr>
                            <td>Contact Name </td>
                            <td><input type='text' class='form-control' value='$contactname' name='contactname'></input></td>
                        </tr>
                        <tr>
                            <td>Address</td></td>
                            <td><input type='text' class='form-control' value='$address' name='address'></input></td>
                        </tr>
                        <tr>
                            <td>City</td>
                            <td><input type='text' class='form-control' value='$city' name='city'></input></td>
                        </tr>
                        <tr>
                            <td>Postal Code</td>
                            <td><input type='text' class='form-control' value='$postalcode' name='postalcode'></input></td>
                        </tr>
                        <tr>
                            <td>Country</td>
                            <td><input type='text' class='form-control' value='$country' name='country'></input></td>
                        </tr>
                        </table>
                    <br>
					<input type='hidden' name='status' value='list_customers_save_edit'>
					<button type='submit' class='btn btn-success'>Submit</button> 
                    <a href='list_customers.php?status=clear&customerid=$customerid' class='btn btn-danger'>Clear</a>
                </form>
            </div>
            ";
        echo $formAddEdit;
    }
    //===============================================================================================================================
    function list_customers_save_edit()
    {
        error_reporting(E_ALL ^ E_NOTICE);

        $q = mysqli_query(
            $GLOBALS["con"],
            "UPDATE customers SET
			customerid='$_POST[customerid]',
			customername='$_POST[customername]',
			contactname='$_POST[contactname]',
			address='$_POST[address]',
			city='$_POST[city]',
			postalcode='$_POST[postalcode]',
			country='$_POST[country]'
		WHERE customerid='$_POST[customerid]'"
        );

        mysqli_close($GLOBALS["con"]);

        header('Location: list_customers.php');
    }
    //===============================================================================================================================
    function list_customers_save_delete()
    {
        $q = mysqli_query($GLOBALS["con"], "DELETE FROM customers WHERE customerid='$_GET[customerid]'");
        header('Location: list_customers.php');
    }
    //===============================================================================================================================
    function list_customers_order()
    {
        $q = mysqli_query(
            $GLOBALS["con"],
            "SELECT *,lastname,firstname,shippername FROM orders 
		JOIN employees ON orders.employeeid=employees.employeeid 
		JOIN shippers ON orders.shipperid=shippers.shipperid 
		WHERE customerid='$_GET[customerid]' ORDER BY orderdate"
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
				<td>$h[firstname] $h[lastname]</td>
				<td>$h[orderdate]</td>
				<td>$h[shippername]<br>($h[phone])</td>
				<td style='text-align:right'>$" . number_format($total, 2) . "</td>
				<td><a href='list_customers.php?status=list_customers_order_detail&customerid=$h[customerid]' class='btn btn-primary btn-sm'>Details</a></td>
			</tr>";
        }
        mysqli_close($GLOBALS["con"]);

        echo

        "<div class='container mt-3'>
		<h1>LIST ORDER</h1>
		<a href='list_customers.php' class='btn btn-warning'>< Back</a><br><br>
                <table class='table table-striped' id='orders' >
                    <tr class='table-success'>
						<th>No</th>
						<th>Employee</th>
						<th>Order Date</th>
						<th>Shipper<br>(Phone)</th>
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
    function list_customers_order_detail()
    {
        //if(!isset($_GET["id"]))$_GET["id"]="";
        $q = mysqli_query(
            $GLOBALS["con"],
            "SELECT *,productname,unit,price FROM orderdetails 
		JOIN products ON orderdetails.productid=products.productid 
		JOIN suppliers ON suppliers.supplierid=products.supplierid 
		JOIN categories ON categories.categoryid=products.categoryid 
		WHERE orderid='$_GET[customerid]'"
        );

        $orderdetails = "";
        $index = 0;
        $total = 0;
        while ($h = mysqli_fetch_array($q)) {
            $orderdetails = $orderdetails .
                "<tr id='orderdetails_$index' data-id='$h[orderdetailid]' onclick=f_rowclick(this.id)>
				<td style='text-align:center'>" . ++$index . "</td>
				<td>$h[productname]</td>
				<td>$h[suppliername]</td>
				<td>$h[categoryname]</td>
				<td>$h[unit]</td>
				<td style='text-align:right'>$h[quantity]</td>
				<td style='text-align:right'>$" . number_format($h["price"], 2) . "</td>
				<td style='text-align:right'>$" . number_format($h["quantity"] * $h["price"], 2) . "</td>
			</tr>";

            $total = $total + ($h["quantity"] * $h["price"]);
        }
        mysqli_close($GLOBALS["con"]);

        echo
        "<table id='orderdetails' class='orderdetails'>
			<tr>
				<th>#</th>
				<th>Product</th>
				<th>Supplier</th>
				<th>Category</th>
				<th>Unit</th>
				<th>Quantity</th>
				<th>Price</th>
				<th>Sub<br>Total</th>
			</tr>
			$orderdetails
			<tr>
				<td style='text-align:right;font-weight:bold' colspan=8>
					Total&nbsp;&nbsp;$" . number_format($total, 2) . "
				</td>
			</tr>
		</table>";
    }
    ?>
</body>

</html>