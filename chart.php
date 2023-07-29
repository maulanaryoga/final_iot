<?php
if (isset($_GET['date'])) {
  $date = $_GET['date'];
} else {
  $timezone = new DateTimeZone('Asia/Jakarta');
  $dateTime = new DateTime();
  $dateTime->setTimezone($timezone);
  $formattedDateTime = $dateTime->format('Y-m-d');
  $date = $formattedDateTime;
}

require 'config.php';
// Prepare the SQL statement using a prepared statement
$check_sql = "SELECT * FROM `sensor_gas` WHERE `tglData` = ?";
$stmt = $conn->prepare($check_sql);
$stmt->bind_param("s", $date);
$stmt->execute();
$check_result = $stmt->get_result();

$data_labels = [];
$data_values = [];

if ($check_result && $check_result->num_rows > 0) {
  while ($row = $check_result->fetch_assoc()) {
    $data_labels[] = $row['jam'];
    $data_values[] = $row['tingkatKebocoran'];
  }
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kelompok 7</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>

<body>
  <div class="container">
    <center>
      <h1 class="my-5">Monitoring Kebocoran Gas</h1>
    </center>

    <canvas id="myChart"></canvas>

    <div class="text-left mt-3">
      <p>NB : Berbahaya jika tingkat kebocoran gas > 1000</p>
    </div>

    <div class="my-3">
      <button type="button" class="btn btn-secondary" onclick="goBack()">Kembali</button>
    </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    function goBack() {
      window.history.back();
    }

    var ctx = document.getElementById('myChart').getContext('2d');
    var chart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: <?php echo json_encode($data_labels); ?>,
        datasets: [{
          label: 'Kebocoran',
          data: <?php echo json_encode($data_values); ?>,
          backgroundColor: 'rgba(0, 123, 255, 0.5)',
          borderColor: 'rgba(0, 123, 255, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  </script>
</body>

</html>
