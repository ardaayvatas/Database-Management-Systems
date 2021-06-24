<!DOCTYPE html>
<html>
<body>
<?php
set_time_limit(0);
$servername = "localhost";
$username = "root";
$password = "mysql";
$dbname = "arda_ayvatas";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} 
$sql = "SELECT branch.branch_name,
city.city_name,
district.district_name,
salesman.salesman_firstname,
salesman.salesman_lastname,
SUM(sale.sale_amount)
FROM sale
LEFT JOIN salesman
ON (sale.salesman_id = salesman.salesman_id)
LEFT JOIN branch
ON (salesman.branch_id = branch.branch_id)
LEFT JOIN city
ON (branch.city_id = city.city_id)
LEFT JOIN district
ON (city.district_id = district.district_id)
WHERE district.district_id= '" . $_REQUEST['selectopt'] . "'
GROUP BY salesman.salesman_id";
$result = mysqli_query($conn,$sql) or die("Error");
$min = 9999;
$max = -9999;
$minfirstname = "";
$minlastname = "";
$maxfirstname = "";
$maxlastname = "";
$counter = 0;
$totalincome = 0;
if (mysqli_num_rows($result) > 0) {
	echo "<table border='1'>";
	echo "<tr><td>Branch</td><td>Province</td><td>District</td><td>EmpNameMostSale</td><td>EmpSurnameMostSale</td><td>SaleAmtMostSale</td><td>EmpNameLeastSale</td><td>EmpSurnameLeastSale</td><td>SaleAmtLeastSale</td><td>TotalIncome</td></tr>";
    while($row = mysqli_fetch_array($result)) {
		$counter++;
		$totalincome = $totalincome + $row[5];
		if ($min >= $row[5])
		{
			$min = $row[5];
			$minfirstname = $row[3];
			$minlastname = $row[4];
		}
		
		if ($max <= $row[5])
		{
			$max = $row[5];
			$maxfirstname = $row[3];
			$maxlastname = $row[4];
		}
		
		if($counter == 4)
		{
			echo "<tr>";
			echo "<td>" . $row[0]. "</td><td>" . $row[1]. "</td><td>" . $row[2]. "</td><td>$maxfirstname</td><td>$maxlastname</td><td>$max</td><td>$minfirstname</td><td>$minlastname</td><td>$min</td><td>$totalincome</td>";
			echo "</tr>";
			$counter=0;
			$min = 9999;
			$max = -9999;
			$totalincome = 0;
		}
	}
	echo "</table>";
} else {
    echo "0 results";
}


$sqlii = "SELECT salesman.salesman_firstname,
salesman.salesman_lastname,
branch.branch_name,
district.district_name,
city.city_name,
customer.customer_firstname,
customer.customer_lastname,
MAX(book.book_price * sale.sale_amount)
FROM sale
LEFT JOIN salesman
ON (sale.salesman_id = salesman.salesman_id)
LEFT JOIN customer
ON (sale.customer_id = customer.customer_id)
LEFT JOIN branch
ON (salesman.branch_id = branch.branch_id)
LEFT JOIN city
ON (branch.city_id = city.city_id)
LEFT JOIN district
ON (city.district_id = district.district_id)
LEFT JOIN book
ON (sale.book_id = book.book_id)
WHERE district.district_id= '" . $_REQUEST['selectopt'] . "'
GROUP BY sale.sale_id";

$resultnew = mysqli_query($conn,$sqlii) or die("Error");

$namecontrol = "";
$maxpaid = -9999;
$empname = "";
$emplname = "";
$empbranch = "";
$customername = "";
$customerlastname = "";
$province = "";
$district ="";
$totalsales = 0;
echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";

if (mysqli_num_rows($resultnew) > 0) {
	echo "<table border='1'>";
	echo "<tr><td>EmpName</td><td>EmpLName</td><td>Branch</td><td>Province</td><td>District</td><td>MaxPaidCustomerName</td><td>MaxPaidCustomerSurname</td><td>TotalSales</td></tr>";
    while($row = mysqli_fetch_array($resultnew)) {
		$totalsales = $totalsales + $row[7];
		if ($namecontrol == "")
		{
			$namecontrol = $row[0];
			if($row[7] > $maxpaid)
			{
				$empname = $row[0];
				$emplname = $row[1];
				$empbranch = $row[2];
				$province = $row[4];
				$customername = $row[5];
				$customerlastname = $row[6];
				$maxpaid = $row[7];
			}
		}
		
		else if ($namecontrol != $row[0])
		{
			$totalsales = $totalsales - $row[7];
			echo "<tr>";
			echo "<td>$empname</td><td>$emplname</td><td>$empbranch</td><td>$province</td><td>" . $row[3]. "</td><td>$customername</td><td>$customerlastname</td><td>$totalsales</td>";
			echo "</tr>";
			$namecontrol=$row[0];
			$empname = $row[0];
			$emplname = $row[1];
			$empbranch = $row[2];
			$province = $row[4];
			$customername = $row[5];
			$customerlastname = $row[6];
			$maxpaid = $row[7];
			$district = $row[3];
			$totalsales = $row[7];
		}
		
		if($row[7] > $maxpaid)
		{
			$empname = $row[0];
			$emplname = $row[1];
			$empbranch = $row[2];
			$province = $row[4];
			$customername = $row[5];
			$customerlastname = $row[6];
			$maxpaid = $row[7];
		}
		
	}
	echo "<td>$empname</td><td>$emplname</td><td>$empbranch</td><td>$province</td><td>$district</td><td>$customername</td><td>$customerlastname</td><td>$totalsales</td>";
	echo "</table>";
} else {
    echo "0 results";
}


mysqli_close($conn);



?>
</body>
</html>