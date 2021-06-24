<?php
set_time_limit(0);
$servername = "localhost";
$username = "root";
$password = "mysql";
// Create connection
$conn = mysqli_connect($servername, $username, $password);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_query($conn,"DROP DATABASE IF EXISTS arda_ayvatas;") or die("Error");
mysqli_query($conn,"CREATE DATABASE arda_ayvatas;") or die("Error");
mysqli_query($conn,"USE arda_ayvatas;") or die("Error");

mysqli_query($conn,"CREATE TABLE IF NOT EXISTS `DISTRICT` (
  `district_id` int(11) NOT NULL AUTO_INCREMENT,
  `district_name` varchar(50) NOT NULL,
  PRIMARY KEY(`district_id`)
) ENGINE=InnoDB;") or die("Error");

mysqli_query($conn,"CREATE TABLE IF NOT EXISTS `CITY` (
  `city_id` int(11) NOT NULL AUTO_INCREMENT,
  `city_name` varchar(50) NOT NULL,
  `district_id` int(11) NOT NULL,
  PRIMARY KEY(`city_id`),
  FOREIGN KEY fk_CITY_district_id (`district_id`) REFERENCES `DISTRICT` (`district_id`)
) ENGINE=InnoDB;") or die("Error");

mysqli_query($conn,"CREATE TABLE IF NOT EXISTS `BRANCH` (
  `branch_id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_name` varchar(50) NOT NULL,
  `city_id` int(11) NOT NULL,
  PRIMARY KEY(`branch_id`),
  FOREIGN KEY fk_BRANCH_city_id (`city_id`) REFERENCES `CITY` (`city_id`)
) ENGINE=InnoDB;") or die("Error");

mysqli_query($conn,"CREATE TABLE IF NOT EXISTS `SALESMAN` (
  `salesman_id` int(11) NOT NULL AUTO_INCREMENT,
  `salesman_firstname` varchar(50) NOT NULL,
  `salesman_lastname` varchar(50) NOT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY(`salesman_id`),
  FOREIGN KEY fk_SALESMAN_branch_id (`branch_id`) REFERENCES `BRANCH` (`branch_id`)
) ENGINE=InnoDB;") or die("Error");

mysqli_query($conn,"CREATE TABLE IF NOT EXISTS `CUSTOMER` (
  `customer_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_firstname` varchar(50) NOT NULL,
  `customer_lastname` varchar(50) NOT NULL,
  PRIMARY KEY(`customer_id`)
) ENGINE=InnoDB;") or die("Error");


mysqli_query($conn,"CREATE TABLE IF NOT EXISTS `BOOK` (
  `book_id` int(11) NOT NULL AUTO_INCREMENT,
  `book_name` varchar(50) NOT NULL,
  `book_price` int(11) NOT NULL,
  PRIMARY KEY(`book_id`)
) ENGINE=InnoDB;") or die("Error");

mysqli_query($conn,"CREATE TABLE IF NOT EXISTS `SALE` (
  `sale_id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `salesman_id` int(11) NOT NULL,
  `sale_amount` int(11) NOT NULL,
  `sale_date` date NOT NULL,
  FOREIGN KEY fk_SALE_book_id (`book_id`) REFERENCES `BOOK` (`book_id`),
  FOREIGN KEY fk_SALE_customer_id (`customer_id`) REFERENCES `CUSTOMER` (`customer_id`),
  FOREIGN KEY fk_SALE_salesman_id (`salesman_id`) REFERENCES `SALESMAN` (`salesman_id`),
  PRIMARY KEY(`sale_id`)
) ENGINE=InnoDB;") or die("Error");

$row = 0;

//district

$filename = "csv/district.csv";
if(!file_exists($filename) || !is_readable($filename))
	return FALSE;

$header = NULL;
if (($handle = fopen($filename, 'r')) !== FALSE)
{
	while (($row = fgetcsv($handle, 1000, ';')) !== FALSE)
	{
		if(!$header)
			$header = $row;
		
		else
		{
			mysqli_query($conn,"INSERT INTO `DISTRICT`
		(`district_id`, `district_name`)
		VALUES('".$row[0]."','".$row[1]."');") or die("Error");
		}
	}
		fclose($handle);
}

//city

$filename = "csv/city.csv";
if(!file_exists($filename) || !is_readable($filename))
	return FALSE;

$header = NULL;
if (($handle = fopen($filename, 'r')) !== FALSE)
{
	while (($row = fgetcsv($handle, 1000, ';')) !== FALSE)
	{
		if(!$header)
			$header = $row;
		
		else
		{
			mysqli_query($conn,"INSERT INTO `CITY`
		(`city_id`, `city_name`,`district_id` )
		VALUES('".$row[0]."','".$row[1]."','".$row[2]."');") or die("Error");
		}
	}
		fclose($handle);
}

//branch

$filename = "csv/branch.csv";
if(!file_exists($filename) || !is_readable($filename))
	return FALSE;

$header = NULL;
if (($handle = fopen($filename, 'r')) !== FALSE)
{
	while (($row = fgetcsv($handle, 1000, ';')) !== FALSE)
	{
		if(!$header)
			$header = $row;
		
		else
		{
			mysqli_query($conn,"INSERT INTO `BRANCH`
		(`branch_id`, `branch_name`,`city_id` )
		VALUES(0,'".$row[0]."','".$row[1]."');") or die("Error");
		}
	}
		fclose($handle);
}

//book
$filename = "csv/book.csv";

if(!file_exists($filename) || !is_readable($filename))
	return FALSE;

$header = NULL;
if (($handle = fopen($filename, 'r')) !== FALSE)
{
	while (($row = fgetcsv($handle, 1000, ';')) !== FALSE)
	{
		if(!$header)
			$header = $row;
		
		else
		{
			$price = rand(5,25);
			mysqli_query($conn,"INSERT INTO `BOOK`
			(`book_id`, `book_name`, `book_price`)
			VALUES(0,'".$row[0]."',$price);") or die("Error");
		}
	}
		fclose($handle);
}

//name and surname	
$filename = "csv/namesurname.csv";
$firstname = array();
$lastname = array();
if(!file_exists($filename) || !is_readable($filename))
	return FALSE;

$header = NULL;
if (($handle = fopen($filename, 'r')) !== FALSE)
{
	while (($row = fgetcsv($handle, 1000, ';')) !== FALSE)
	{
		if(!$header)
			$header = $row;
		
		else
		{
			$firstname[] = $row[0];
			$lastname[] = $row[1];
		}
	}
		fclose($handle);
}

//salesman
for($i=0;$i<81*5;$i++)
{
	for($j=0;$j<4;$j++)
	{
		mysqli_query($conn,"INSERT INTO `SALESMAN`
		(`salesman_id`, `salesman_firstname`, `salesman_lastname`, `branch_id`)
		VALUES(0,'".$firstname[rand(0,499)]."','".$lastname[rand(0,499)]."',$i+1);") or die("Error");
	}
}


//customer
for($i=0;$i<81*5*5;$i++)
{
	mysqli_query($conn,"INSERT INTO `CUSTOMER`
	(`customer_id`, `customer_firstname`, `customer_lastname`)
	VALUES(0,'".$firstname[rand(0,499)]."','".$lastname[rand(0,499)]."');") or die("Error");
}

//sale
for($i=0;$i<81*5*5;$i++)
{
	for($j=0;$j<10;$j++)
	{
		$booknum = rand(1,500);
		$salesmannum = rand(1,1620);
		$saleamountnum = rand(1,10);
		$a = rand(1980,2021);
		$b = rand(1,12);
		$c = rand(1,28);
		mysqli_query($conn,"INSERT INTO `SALE`
		(`sale_id`, `book_id`, `customer_id`, `salesman_id`, `sale_amount`, `sale_date`)
		VALUES(0,$booknum,$i+1,$salesmannum,$saleamountnum,'$a-$b-$c');") or die("Error");
	}
}


mysqli_close($conn);
?>