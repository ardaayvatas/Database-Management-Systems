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
$sql = "SELECT customer.customer_firstname,
customer.customer_lastname,
SUM(sale.sale_amount),
SUM(sale.sale_amount*book.book_price),
branch.branch_id
FROM sale
LEFT JOIN customer
ON (sale.customer_id = customer.customer_id)
LEFT JOIN book
ON (sale.book_id = book.book_id)
LEFT JOIN salesman
ON (sale.salesman_id = salesman.salesman_id)
LEFT JOIN branch
ON (salesman.branch_id = branch.branch_id)
WHERE branch.branch_id = '" . $_REQUEST['selectopt'] . "'
GROUP BY customer.customer_id";


$result = mysqli_query($conn,$sql) or die("Error");
if (mysqli_num_rows($result) > 0) {
	echo "<table border='1'>";
	echo "<tr><td>customer name</td><td>customer surname</td><td># of sales</td><td>total price</td></tr>";
    while($row = mysqli_fetch_array($result)) {
			echo "<tr>";
			echo "<td>" . $row[0]. "</td><td>" . $row[1]. "</td><td>" . $row[2]. "</td><td>" . $row[3]. "</td>";
			echo "</tr>";
	}
	echo "</table>";
} else {
    echo "0 results";
}

$sqlii = "SELECT salesman.salesman_firstname,
salesman.salesman_lastname,
SUM(sale.sale_amount),
SUM(sale.sale_amount*book.book_price),
branch.branch_id
FROM sale
LEFT JOIN salesman
ON (sale.salesman_id = salesman.salesman_id)
LEFT JOIN book
ON (sale.book_id = book.book_id)
LEFT JOIN branch
ON (salesman.branch_id = branch.branch_id)
WHERE branch.branch_id = '" . $_REQUEST['selectopt'] . "'
GROUP BY salesman.salesman_id";

$resultnew = mysqli_query($conn,$sqlii) or die("Error");

echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";

$result = mysqli_query($conn,$sqlii) or die("Error");
if (mysqli_num_rows($resultnew) > 0) {
	echo "<table border='1'>";
	echo "<tr><td>employee name</td><td>employee surname</td><td># of sales</td><td>total price</td></tr>";
    while($row = mysqli_fetch_array($resultnew)) {
			echo "<tr>";
			echo "<td>" . $row[0]. "</td><td>" . $row[1]. "</td><td>" . $row[2]. "</td><td>" . $row[3]. "</td>";
			echo "</tr>";
	}
	echo "</table>";
} else {
    echo "0 results";
}


mysqli_close($conn);



?>
</body>
</html>