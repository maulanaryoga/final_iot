<?php

$dataTingkatKebocoran = $_GET['tingkatKebocoran'];

echo "Data dari url : <br> tingkatKebocoran : " . $dataTingkatKebocoran;

require 'config.php';

$timezone = new DateTimeZone('Asia/Jakarta');
$dateTime = new DateTime();
$dateTime->setTimezone($timezone);


$formattedDateTime = $dateTime->format('Y-m-d'); //tanggal
$formattedTime = $dateTime->format('H:i'); //jam

$sql = "INSERT INTO `sensor_gas` (`idSensor`, `tingkatKebocoran`, `tglData` , `jam`) VALUES (NULL, '$dataTingkatKebocoran', '$formattedDateTime','$formattedTime');";
$result = $conn->query($sql);

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
