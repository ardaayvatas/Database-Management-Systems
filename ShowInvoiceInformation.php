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
$sql = "SELECT district.district_name,
city.city_name,
branch.branch_name,
salesman.salesman_firstname,
salesman.salesman_lastname,
book.book_name,
sale.sale_amount,
sale.sale_date
FROM sale
LEFT JOIN customer
ON (sale.customer_id = customer.customer_id)
LEFT JOIN salesman
ON (sale.salesman_id = salesman.salesman_id)
LEFT JOIN book
ON (sale.book_id = book.book_id)
LEFT JOIN branch
ON (salesman.branch_id = branch.branch_id)
LEFT JOIN city
ON (branch.city_id = city.city_id)
LEFT JOIN district
ON (city.district_id = district.district_id)
WHERE customer.customer_firstname ='" . $_POST['customername'] . "' AND customer.customer_lastname = '" . $_POST['customersurname'] . "'";

$result = mysqli_query($conn,$sql) or die("Error");
if (mysqli_num_rows($result) > 0) {
	echo "<table border='1'>";
	echo "<tr><td>district name</td><td>province name</td><td>branch name</td><td>employee name</td><td>employee surname</td><td>book name</td><td>sale amount</td><td>date</td></tr>";
    while($row = mysqli_fetch_array($result)) 
	{
			echo "<tr>";
			echo "<td>" . $row[0]. "</td><td>" . $row[1]. "</td><td>" . $row[2]. "</td><td>" . $row[3]. "</td><td>" . $row[4]. "</td><td>" . $row[5]. "</td><td>" . $row[6]. "</td><td>" . $row[7]. "</td>";
			echo "</tr>";
	}
	echo "</table>";
} else {
    echo "0 results";
}