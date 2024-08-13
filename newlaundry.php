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
            position: relative;
            cursor: pointer;
        }

        /* For better visibility */
        .load-column h3 {
            margin-top: 0;
        }

        .service-options {
            display: none;
            margin-top: 10px;
        }

        .service-options input {
            margin-right: 10px;
        }

        #totalPrice {
            margin-top: 20px;
            font-weight: bold;
            font-size: 18px;
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
        </div>

        <div class="tab">
            <h1>Choose Laundry Type</h1>
            <input type="radio" name="type" id="wash" value="Wash">
            <label for="wash">Wash (P75)</label></br>
            <input type="radio" name="type" id="washdry" value="Wash + Dry">
            <label for="washdry">Wash + Dry (P160)</label></br>
            <input type="radio" name="type" id="washdryfold" value="Wash + Dry + Fold">
            <label for="washdryfold">Wash + Dry + Fold (P200)</label>
        </div>
        <button type="button" id="processButton">Process</button>
    </form>

    <!-- Container to display load details -->
    <div id="loadDetails" class="load-container"></div>

    <!-- Display total price -->
    <div id="totalPrice"></div>

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
            var totalPriceElement = document.getElementById('totalPrice');

            if (isNaN(inputWeight) || inputWeight < 0.01) {
                alert('Please enter a valid weight.');
                return;
            }

        var numberOfLoads = calculateLoads(inputWeight);
        loadsElement.textContent = numberOfLoads + (numberOfLoads > 1 ? " Loads" : " Load");

        var balancedWeightPerLoad = (inputWeight / numberOfLoads).toFixed(2);

        // Get selected laundry type and price
        var laundryTypes = document.getElementsByName('type');
        var selectedType = null;
        var pricePerLoad = 0;
        for (var i = 0; i < laundryTypes.length; i++) {
            if (laundryTypes[i].checked) {
                selectedType = laundryTypes[i].value;
                if (selectedType === "Wash") pricePerLoad = 75;
                else if (selectedType === "Wash + Dry") pricePerLoad = 160;
                else if (selectedType === "Wash + Dry + Fold") pricePerLoad = 200;
                break;
            }
        }

        if (!selectedType) {
            alert('Please select a laundry type.');
            return;
        }

        // Calculate base total price
        var totalPrice = pricePerLoad * numberOfLoads;

        // Clear previous load details
        loadDetailsContainer.innerHTML = '';

        // Create columns for each load
        for (var i = 1; i <= numberOfLoads; i++) {
            var column = document.createElement('div');
            column.className = 'load-column';
            column.dataset.loadNumber = i; // Store load number
            column.dataset.loadPrice = pricePerLoad; // Store base price for the load
            column.dataset.additionalPrice = 0; // Store additional price for the load

            column.innerHTML = `
                <h3>Load ${i}</h3>
                <p>Weight: ${balancedWeightPerLoad} kg</p>
                <p>Type: ${selectedType}</p>
                <p>Price: P${pricePerLoad}</p>
                <div class="service-options">
                    <h4>Additional Services</h4>
                    <label for="detergent-${i}">Detergent (P20 per unit)</label>
                    <input type="number" id="detergent-${i}" data-price="20" min="0" value="0" onchange="updateLoadPrice(${i})"><br>
                    <label for="fabric-${i}">Fabric (P15 per unit)</label>
                    <input type="number" id="fabric-${i}" data-price="15" min="0" value="0" onchange="updateLoadPrice(${i})">
                </div>
            `;

            // Add click event to show services
            column.addEventListener('click', function(event) {
                var serviceOptions = this.querySelector('.service-options');
                serviceOptions.style.display = (serviceOptions.style.display === 'none' || serviceOptions.style.display === '') ? 'block' : 'none';
            });

            // Prevent service option clicks from toggling the display of the service-options
            var serviceInputs = column.querySelectorAll('.service-options input[type="number"]');
            serviceInputs.forEach(function(input) {
                input.addEventListener('click', function(event) {
                    event.stopPropagation(); // Prevent event from bubbling up to the parent
                });
            });

            loadDetailsContainer.appendChild(column);
        }

            // Display total price
            totalPriceElement.textContent = `Total Price: P${totalPrice}`;
        });

        function updateLoadPrice(loadNumber) {
            var loadColumn = document.querySelector(`.load-column[data-load-number="${loadNumber}"]`);
            var basePrice = parseFloat(loadColumn.dataset.loadPrice);
            var additionalPrice = 0;

            // Calculate additional price based on quantities
            var serviceOptions = loadColumn.querySelectorAll('.service-options input[type="number"]');
            serviceOptions.forEach(function(option) {
                var quantity = parseFloat(option.value);
                var pricePerUnit = parseFloat(option.dataset.price);
                additionalPrice += quantity * pricePerUnit;
            });

            // Update the additional price data attribute
            loadColumn.dataset.additionalPrice = additionalPrice;

            // Update the total price
            var totalPrice = 0;
            var allLoadColumns = document.querySelectorAll('.load-column');
            allLoadColumns.forEach(function(column) {
                var columnBasePrice = parseFloat(column.dataset.loadPrice);
                var columnAdditionalPrice = parseFloat(column.dataset.additionalPrice);
                totalPrice += (columnBasePrice + columnAdditionalPrice);
            });

            // Display the updated total price
            document.getElementById('totalPrice').textContent = `Total Price: P${totalPrice}`;
        }

    </script>
</body>
</html>
