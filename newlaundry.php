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
        </div>

        <p id="result"></p>
        <p id="DivisorResult"></p>

        <div class="tab">
            <h1>Choose Laundry Type</h1>
            <input type="radio" id="type" value="Wash">
            <label for="type">Wash</label></br>
            <input type="radio" id="type" value="Wash + Dry">
            <label for="type">Wash + Dry</label></br>
            <input type="radio" id="type" value="Wash + Dry + Fold">
            <label for="type">Wash + Dry + Fold</label>
        </div>
        <button type="button">Next</button>

    </form>

    <script>
        document.getElementById('userInput').addEventListener('input', function() {
            var input = document.getElementById('userInput').value;
            var result = document.getElementById('result');

            if (input <= 8) {
                result.textContent = 1 + " Load";
            } else if (input <= 16) {
                result.textContent = 2 + " Loads";
            } else if (input <= 24) {
                result.textContent = 3 + " Loads";
            } else {
                result.textContent = 4 + " Loads";
            }
        });

        function performDivision() {
            var input = document.getElementById('userInput').value;
            // var divisor = document.getElementById('divisorInput').value;
            var divisorResult = document.getElementById('DivisorResult');

            if (input <= 8) {
                var divisionResult = input / 1;
                divisorResult.textContent = "Result: " + divisionResult;
            } else if (input <= 16) {
                var divisionResult = input / 2;
                divisorResult.textContent = "Result: " + divisionResult;
            } else if (input <= 24) {
                var divisionResult = input / 3;
                divisorResult.textContent = "result: " + divisionResult;
            } else {
                var divisionResult = input / 4;
                divisorResult.textContent = "result: " + divisionResult;
            }
        }

        document.getElementById('userInput').addEventListener('input', performDivision);
        // document.getElementById('divisorInput').addEventListener('input', performDivision);
    </script>
</body>
</html>