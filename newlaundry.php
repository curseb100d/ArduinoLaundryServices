<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS File Link -->
    <link rel="stylesheet" href="css/styles.css">

    <title>New Laundry</title>
</head>
<body>
    <?php include 'navigation.php' ?>

    <form id="inputForm">
        <div class="tab">
            <h1>Kilo</h1>
            <p>Kindly weight your laundry</p>
            <p>Minimum: 8kg</p>
            <input type="number" id="userInput" placeholder="Kilo">
            <p id="result"></p>
        </div>

        <div class="tab">
            <h1>Choose Laundry Type</h1>
            <input type="radio" id="type" value="Wash">
            <label for="type">Wash</label></br>
            <input type="radio" id="type" value="Wash + Dry">
            <label for="type">Wash + Dry</label></br>
            <input type="radio" id="type" value="Wash + Dry + Fold">
            <label for="type">Wash + Dry + Fold</label>
        </div>

    </form>

    <script>
        document.getElementById('userInput').addEventListener('input', function() {
            var input = document.getElementById('userInput').value;
            var result = document.getElementById('result');

            if (input <= 8) {
                result.textContent = 1;
            } else if (input <= 16) {
                result.textContent = 2;
            } else if (input <= 24) {
                result.textContent = 3;
            } else {
                result.textContent = 4;
            }
        });
    </script>
</body>
</html>