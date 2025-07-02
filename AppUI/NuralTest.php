<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Neural Prediction</title>
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
            max-width: 500px;
            width: 100%;
        }

        h2 {
            text-align: center;
            color: #00bcd4;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 10px;
        }

        input[type="number"] {
            padding: 10px;
            font-size: 16px;
            margin-top: 5px;
            border-radius: 6px;
            border: none;
        }

        input[type="submit"] {
            background-color: #8e44ad;
            color: white;
            border: none;
            padding: 12px;
            margin-top: 20px;
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
        <h2>Neural Prediction</h2>
        <form method="POST">
            <label for="co">CO AQI Value:</label>
            <input type="number" name="co" id="co" required step="any">

            <label for="ozone">Ozone AQI Value:</label>
            <input type="number" name="ozone" id="ozone" required step="any">

            <label for="no2">NO2 AQI Value:</label>
            <input type="number" name="no2" id="no2" required step="any">

            <label for="pm25">PM2.5 AQI Value:</label>
            <input type="number" name="pm25" id="pm25" required step="any">

            <input type="submit" value="Predict">
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $co = $_POST['co'];
            $ozone = $_POST['ozone'];
            $no2 = $_POST['no2'];
            $pm25 = $_POST['pm25'];

            $data = array("features" => [$co, $ozone, $no2, $pm25]);
            $options = array(
                'http' => array(
                    'header'  => "Content-type: application/json",
                    'method'  => 'POST',
                    'content' => json_encode($data)
                )
            );
            $context  = stream_context_create($options);
            $result = file_get_contents('http://localhost:6060/neural', false, $context);
            $response = json_decode($result, true);

            echo "<div class='result'><strong>Predicted Result:</strong> ". $result . "</div>";
        }
        ?>
    </div>
</body>

</html>