<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employees</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>


    <?php
    $con = mysqli_connect("localhost", "root", "", "northwind");
    //===============================================================================================================================

    if (!isset($_GET["status"])) $_GET["status"] = "list_employees";
    if (!isset($_POST["status"])) $_POST["status"] = "list_employees";

    if ($_POST["status"] == "list_employees_save_edit") list_employees_save_edit();
    elseif ($_GET["status"] == "list_employees_save_delete") list_employees_save_delete();
    elseif ($_GET["status"] == "list_employees") list_employees();
    elseif ($_GET["status"] == "list_employees_new") list_employees_new();
    elseif ($_GET["status"] == "list_employees_new_save") list_employees_new_save();
    elseif ($_GET["status"] == "list_employees_edit") list_employees_edit();
    elseif ($_GET["status"] == "list_employees_order") list_employees_order();
    // elseif ($_GET["status"] == "list_employees_order_detail") list_employees_order_detail();
    elseif ($_GET["status"] == "clear") list_employees_edit();
    //===============================================================================================================================

    function list_employees()
    {

        error_reporting(E_ALL ^ E_NOTICE);
        $q = mysqli_query($GLOBALS["con"], "SELECT * FROM employees");

        $no = 0;
        $list_employees = "";
        while ($h = mysqli_fetch_array($q)) {
            $list_employees = $list_employees .
                "<tr>
                <td>" . ++$no . "</td>
                <td>$h[firstname], $h[lastname]</td>
                <td>$h[birthdate]</td>
                <td>$h[photo]</td>
                <td>$h[notes]</td>
                <td>
                        <a href='list_employees.php?status=list_employees_edit&employeeid=$h[employeeid]' class='btn btn-success btn-sm'>Edit</a> 
                        <a href='list_employees.php?status=list_employees_save_delete&employeeid=$h[employeeid]' class='btn btn-danger btn-sm'>Del</a>
                        <a href='list_employees.php?status=list_employees_order&employeeid=$h[employeeid]' class='btn btn-primary btn-sm'>Orders</a>
                </td>
            </tr>";
        }
        $list_employees =
            "<div class='container mt-3'>
     <h2>List Employees</h2>
     <a href='index.php' class='btn btn-warning'>< Back</a><br><br>
        <p><a href='list_employees.php?status=list_employees_new' class='btn btn-info btn-sm'>Add New</a></p>
        <table class='table table-striped'>
        <tr class='table-success'>
                        <th>No</th>
                        <th>Employees Name</th>
                        <th>Birth date</th>
                        <th>Photos</th>
                        <th>Notes</th>
                        <th><center>Action</center></th>
                    </tr>
                    $list_employees
                </table>
            </div>";

        mysqli_close($GLOBALS["con"]);
        echo $list_employees;
    }

    //===============================================================================================================================
    function list_employees_new()
    {

        $option_tanggal = "<option value='' style='display:none'>- Pilih Tanggal -</option>";
        for ($i = 1; $i <= 31; $i++) {
            $option_tanggal = $option_tanggal . "<option value=$i>$i</option>";
        }

        $bulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $option_bulan = "<option value='' style='display:none'>- Pilih Bulan -</option>";
        for ($i = 1; $i <= 12; $i++) {
            $option_bulan = $option_bulan . "<option value=$i>$bulan[$i]</option>";
        }


        $formAddNew =
            "<div class='container mt-3'>
        <h2>NEW EMPLOYEES</h2>
        <br>
        <a href='list_employees.php' class='btn btn-warning'>< Back</a><br><br>
        <form action='list_employees.php?status=list_employees_new_save' method='post'>
        <table>
            <tr>
                <td>First Name</td>
                <td><input type='text' class='form-control' name='firstname'></td>
            </tr>
            <tr>
                <td>Last  Name</td>
                <td><input type='text' class='form-control' name='lastname'></td>
            </tr>
            <tr>
                <td>Birth Date</td>
                <td><input type='date' class='form-control' name='birthdate'></td>
            </tr>
            <tr>
                <td>Photos</td>
                <td><input type='text' class='form-control' name='photo'></td>
            </tr>
            <tr>
                <td>Notes</td>
                <td><input type='text' class='form-control' name='notes'></td>
            </tr>
        </table>
             <br>
                <button type='submit' class='btn btn-success'>Submit</button> 
                <a href='' class='btn btn-danger'>Clear</a>
            </form>
        </div>
        ";
        echo $formAddNew;
    }

    //===============================================================================================================================
    function list_employees_new_save()
    {
        $q = mysqli_query($GLOBALS["con"], "INSERT INTO employees(firstname, lastname, birthdate, photo, notes) VALUES ('$_POST[firstname]',
    '$_POST[lastname]','$_POST[tahun]-$_POST[bulan]-$_POST[tanggal]','$_POST[photo]','$_POST[notes]')");
        mysqli_close($GLOBALS["con"]);

        header('Location: list_employees.php');
    }

    //===============================================================================================================================
    function list_employees_edit()
    {
        $q = mysqli_query($GLOBALS["con"], "SELECT * FROM employees WHERE employeeid='$_GET[employeeid]'");
        while ($h = mysqli_fetch_array($q)) {
            $employeeid = $h["employeeid"];
            $firstname = $h["firstname"];
            $lastname = $h["lastname"];
            $birthdate = $h["birthdate"];
            $photo = $h["photo"];
            $notes = $h["notes"];
        }

        if ($_GET["status"] == "clear") {
            $employeeid = $firstname = $lastname = $birthdate = $photo = $notes = "";
        }
        mysqli_close($GLOBALS["con"]);

        $formAddEdit =
            "<div class='container mt-3'>
        <h2>EDIT EMPLOYEES</h2><br>
        <a href='list_employees.php' class='btn btn-warning'>< Back</a><br><br>
        <form action='list_employees.php?status=list_employees_save_edit' method='post'>
        <input type='hidden' name='employeeid' value='$employeeid'/>
            <table>
                <tr>
                    <td>First Name </td>
                    <td><input type='text' class='form-control' value='$firstname' name='firstname'></td>
                </tr>
                <tr>
                    <td>Last Name </td>
                    <td><input type='text' class='form-control' value='$lastname' name='lastname'></td>
                </tr>
                <tr>
                    <td>Birth Date</td>
                    <td><input type='date' class='form-control' value='$birthdate' name='birthdate'></td>
                </tr>
                <tr>
                    <td>Photo</td>
                    <td><input type='text' class='form-control' value='$photo' name='photo'></td>
                </tr>
                <tr>
                    <td>Notes</td>
                    <td><input type='text' class='form-control' value='$notes' name='notes'></td>
                </tr>
                </table>
                <br>
                <input type='hidden' name='status' value='list_employees_save_edit'>
				<button type='submit' class='btn btn-success'>Submit</button>
                <a href='list_employees.php?status=clear&employeeid=$employeeid' class='btn btn-danger'>Clear</a> 
                </form>
            </div>";

        echo $formAddEdit;
    }

    //===============================================================================================================================
    function list_employees_save_edit()
    {
        error_reporting(E_ALL ^ E_NOTICE);

        $q = mysqli_query(
            $GLOBALS["con"],
            "UPDATE employees SET
			employeeid='$_POST[employeeid]',
			firstname='$_POST[firstname]',
			lastname='$_POST[lastname]',
			birthdate='$_POST[birthdate]',
			photo='$_POST[photo]',
			notes='$_POST[notes]'
		WHERE employeeid='$_POST[employeeid]'"
        );

        mysqli_close($GLOBALS["con"]);

        header('Location: list_employees.php');
    }

    //===============================================================================================================================
    function list_employees_save_delete()
    {
        $q = mysqli_query($GLOBALS["con"], "DELETE FROM employees WHERE employeeid='$_GET[employeeid]'");
        header('Location: list_employees.php');
    }
    //===============================================================================================================================
    function list_employees_order()
    {
        $q = mysqli_query(
            $GLOBALS["con"],
            "SELECT *,customername,contactname,shippername FROM orders 
    JOIN customers ON orders.customerid=customers.customerid 
    JOIN shippers ON orders.shipperid=shippers.shipperid 
    WHERE employeeid='$_GET[employeeid]' ORDER BY orderdate"
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
            <td>$h[customername] $h[contactname]</td>
            <td>$h[orderdate]</td>
            <td>$h[shippername]<br>($h[phone])</td>
            <td style='text-align:right'>$" . number_format($total, 2) . "</td>
            <td><a href='list_employees.php?status=list_employees_order_detail&employeeid=$h[employeeid]' class='btn btn-primary btn-sm'>Details</a></td>
        </tr>";
        }
        mysqli_close($GLOBALS["con"]);

        echo

        "<div class='container mt-3'>
    <h1>LIST ORDER</h1>
    <a href='list_employees.php' class='btn btn-warning'>< Back</a><br><br>
            <table class='table table-striped' id='orders' >
                <tr class='table-success'>
                    <th>No</th>
                    <th>Customer</th>
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
    ?>

</body>

</html>