<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- CSS File Link -->
  <link rel="stylesheet" href="css/styles.css">

  <title>New Laundry</title>
  <style>
    /* Add some basic styles for the columns */
    .load-container {
      display: flex;
      gap: 20px;
      margin-top: 20px;
    }

    .load-column {
      flex: 1;
      border: 1px solid #ccc;
      padding: 10px;
      border-radius: 5px;
    }

    /* For better visibility */
    .load-column h3 {
      margin-top: 0;
    }
  </style>
</head>
<body>
  <?php include 'navigation.php' ?>

  <form id="inputNewLaundryForm">
    <div class="tab">
      <h1>Kilo</h1>
      <p>Kindly weigh your laundry</p>
      <p>Minimum: <strong>8kg</strong></p>
      <input type="number" step="0.01" id="weighingInput" placeholder="Kilo">
      <p id="loads"></p>
      <p id="divisorLoads"></p> 
    </div>

    <div class="tab">
      <h1>Choose Laundry Type</h1>
      <input type="radio" name="type" value="Wash" id="wash">
      <label for="wash">Wash</label></br>
      <input type="radio" name="type" value="Wash + Dry" id="washdry">
      <label for="washdry">Wash + Dry</label></br>
      <input type="radio" name="type" value="Wash + Dry + Fold" id="washdryfold">
      <label for="washdryfold">Wash + Dry + Fold</label>
    </div>
    <button type="button" id="processButton">Process</button>
  </form>

  <!-- Container to display load details -->
  <div id="loadDetails" class="load-container"></div>

  <script>
    function calculateLoads(weight) {
      if (weight <= 8) return 1;
      else if (weight <= 16) return 2;
      else if (weight <= 24) return 3;
      else if (weight <= 32) return 4;
      else if (weight <= 40) return 5;
      else return 6;
    }

    document.getElementById('processButton').addEventListener('click', function() {
      var inputWeight = parseFloat(document.getElementById('weighingInput').value);
      var loadsElement = document.getElementById('loads');
      var loadDetailsContainer = document.getElementById('loadDetails');

      if (isNaN(inputWeight) || inputWeight < 0.01) {
        alert('Please enter a valid weight.');
        return;
      }

      var numberOfLoads = calculateLoads(inputWeight);
      loadsElement.textContent = numberOfLoads + (numberOfLoads > 1 ? " Loads" : " Load");

      var balancedWeightPerLoad = (inputWeight / numberOfLoads).toFixed(2);

      // Get selected laundry type
      var laundryTypes = document.getElementsByName('type');
      var selectedType = null;
      for (var i = 0; i < laundryTypes.length; i++) {
        if (laundryTypes[i].checked) {
          selectedType = laundryTypes[i].value;
          break;
        }
      }

      if (!selectedType) {
        alert('Please select a laundry type.');
        return;
      }

      // Clear previous load details
      loadDetailsContainer.innerHTML = '';

      // Create columns for each load
      for (var i = 1; i <= numberOfLoads; i++) {
        var column = document.createElement('div');
        column.className = 'load-column';
        column.innerHTML = `
          <h3>Load ${i}</h3>
          <p>Weight: ${balancedWeightPerLoad} kg</p>
          <p>Type: ${selectedType}</p>
        `;
        loadDetailsContainer.appendChild(column);
      }
    });
  </script>
</body>
</html>
