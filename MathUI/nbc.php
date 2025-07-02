<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Pollutant Category Selection</title>
    <style>
        body {
            background-color: #1e1e2f;
            color: #e0e0e0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #2a2a40;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 0 25px rgba(0, 188, 212, 0.3);
            max-width: 600px;
            width: 100%;
        }

        h2 {
            text-align: center;
            color: #00bcd4;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            margin-bottom: 5px;
        }

        select {
            padding: 12px;
            font-size: 16px;
            border-radius: 6px;
            border: none;
            width: 100%;
        }

        input[type="submit"] {
            background-color: #8e44ad;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #a657c6;
        }

        .result {
            margin-top: 20px;
            background-color: #333;
            padding: 15px;
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Select AQI Categories for Pollutants</h2>
        <form method="POST">
            <?php
            $pollutants = ['CO AQI Value', 'Ozone AQI Value', 'NO2 AQI Value', 'PM2.5 AQI Value'];
            $categories = ['Good', 'Moderate', 'Unhealthy for Sensitive Groups', 'Unhealthy', 'Very Unhealthy', 'Hazardous'];

            foreach ($pollutants as $pollutant) {
                echo "<label for='{$pollutant}'>{$pollutant}:</label>";
                echo "<select name='categories[" . htmlspecialchars($pollutant) . "]' required>";
                echo "<option value=''>-- Select Category --</option>";
                foreach ($categories as $category) {
                    echo "<option value='" . htmlspecialchars($category) . "'>$category</option>";
                }
                echo "</select>";
            }
            ?>
            <input type="submit" value="Submit">
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['categories'])) {
            $categories = $_POST['categories'];
            $co = $categories['CO AQI Value'];
            $ozone = $categories['Ozone AQI Value'];
            $no2 = $categories['NO2 AQI Value'];
            $pm25 = $categories['PM2.5 AQI Value'];

            $data = array("features" => [$co, $ozone, $no2, $pm25]);

            $options = array(
                'http' => array(
                    'header'  => "Content-type: application/json",
                    'method'  => 'POST',
                    'content' => json_encode($data)
                )
            );
            $context  = stream_context_create($options);
            $result = file_get_contents('http://localhost:6060/categorical_NB', false, $context);
            $response = json_decode($result, true);

            echo "<div class='result'><strong>Predicted Result:</strong> " . htmlspecialchars($result) . "</div>";
        }
        ?>
    </div>
</body>

</html>