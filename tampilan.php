<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelompok 7</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <style>
        .table-container {
            max-height: 400px;
            overflow-y: scroll;
        }

        .table-striped tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        .table-striped tbody tr:nth-child(even) {
            background-color: #ffffff;
        }
    </style>
</head>

<body>
    <div class="container">
        <center>
            <h1 class="my-5">Monitoring Kebocoran Gas</h1>
        </center>

        <div class="table-container">
            <?php
            require 'config.php';

            // Execute a SELECT query to retrieve data from the datasensor table
            $query = "SELECT * FROM sensor_gas GROUP BY tglData;";
            $result = mysqli_query($conn, $query);

            // Check if the query executed successfully
            if ($result) {
                // Display the data in an HTML table
                echo "<table class='table table-striped'>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Tingkat Kebocoran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>";

                // Fetch rows from the result set
                $row_num = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    $row_num++;
                    $row_class = $row_num % 2 == 0 ? 'even' : 'odd';
                    echo "<tr class='$row_class'>
                        <td>{$row['idSensor']}</td>
                        <td>{$row['tglData']}</td>
                        <td>{$row['jam']}</td>
                        <td>{$row['tingkatKebocoran']}</td>
                        <td><a type='button' href='chart.php?date={$row['tglData']}' class='btn btn-primary'>Lihat Grafik</a></td>
                    </tr>";
                }

                echo "</tbody></table>";

                // Free the result set
                mysqli_free_result($result);
            } else {
                echo "Error: " . mysqli_error($conn);
            }

            // Close the database connection
            mysqli_close($conn);
            ?>
        </div>

        <span id="status-message"></span>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
</body>

</html>
