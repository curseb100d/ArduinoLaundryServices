<?php
// Include database connection
@include 'config.php';

// Fetch customers from customeruser table using PDO
$stmt = $pdo->prepare("SELECT customeruser_id, firstname, lastname, email, number FROM customeruser");
$stmt->execute();
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
// Include database connection
@include 'config.php';

// Fetch laundry products from database
$select_products = mysqli_query($conn, "SELECT * FROM laundry_products WHERE name IN ('Surf', 'Ariel', 'Downy')");
?>

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

        <!-- <div class="tab">
            <h1>Choose Laundry Type</h1>
            <input type="radio" name="type" id="wash" value="Wash">
            <label for="wash">Wash (P75)</label></br>
            <input type="radio" name="type" id="washdry" value="Wash + Dry">
            <label for="washdry">Wash + Dry (P160)</label></br>
            <input type="radio" name="type" id="washdryfold" value="Wash + Dry + Fold">
            <label for="washdryfold">Wash + Dry + Fold (P200)</label>
        </div> -->
        <button type="button" id="processButton">Process</button>
    </form>

    <!-- Container to display load details -->
    <div id="loadDetails" class="load-container"></div>

    <!-- Display total price -->
    <div id="totalPrice"></div>

    <!-- Save Button -->
    <button type="button" id="saveButton">Save</button>

    <!-- Customer Details Form (Modal or Section) -->
    <div id="customerDetailsForm" style="display:none;">
        <h2>Select Customer</h2>
        <select id="customerSelect" required>
            <option value="">-- Select a Customer --</option>
            <?php foreach($customers as $customer) { ?>
                <option value="<?php echo $customer['customeruser_id']; ?>">
                    <?php echo $customer['firstname'] . ' ' . $customer['lastname'] . ' (' . $customer['email'] . ')'; ?>
                </option>
            <?php } ?>
        </select>
        <br><br>

        <h2>Enter Customer Details</h2>
        <input type="text" id="firstName" placeholder="First Name" required><br>
        <input type="text" id="lastName" placeholder="Last Name" required><br>
        <input type="email" id="email" placeholder="Email" required><br>
        <input type="text" id="phone" placeholder="Phone Number" required><br>
        <button type="button" id="submitOrder">Submit Order</button>
    </div>

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

        // Clear previous load details
        loadDetailsContainer.innerHTML = '';

        // Create columns for each load
        for (var i = 1; i <= numberOfLoads; i++) {
            var column = document.createElement('div');
            column.className = 'load-column';
            column.dataset.loadNumber = i; // Store load number
            column.dataset.loadPrice = 0; // Initialize base price for the load
            column.dataset.additionalPrice = 0; // Store additional price for the load

            column.innerHTML = `
                    <h3>Load ${i}</h3>
                    <p>Weight: ${balancedWeightPerLoad} kg</p>
                    <div>
                        <h4>Select Laundry Type</h4>
                        <input type="radio" name="type-${i}" value="Wash" data-price="75" onclick="updateLoadType(${i})">
                        <label for="wash">Wash (P75)</label><br>
                        <input type="radio" name="type-${i}" value="Wash + Dry" data-price="160" onclick="updateLoadType(${i})">
                        <label for="washdry">Wash + Dry (P160)</label><br>
                        <input type="radio" name="type-${i}" value="Wash + Dry + Fold" data-price="200" onclick="updateLoadType(${i})">
                        <label for="washdryfold">Wash + Dry + Fold (P200)</label>
                    </div>
                    <div class="service-options">
                        <h4>Additional Services</h4>
                        <?php while($row = mysqli_fetch_assoc($select_products)) { ?>
                        <label for="product-${i}-${<?php echo $row['id']; ?>}"><?php echo $row['name']; ?> (P<?php echo $row['price']; ?> per unit)</label>
                        <input type="number" id="product-${i}-${<?php echo $row['id']; ?>}" data-price="<?php echo $row['price']; ?>" min="0" value="0" onchange="updateLoadPrice(${i})"><br>
                        <?php } ?>
                    </div>
                    <p id="totalLoadPrice-${i}" class="load-total-price">Total Load Price: P0</p>
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

        updateTotalPrice(); // Calculate total price after rendering all loads
    });

    function updateLoadType(loadNumber) {
        var loadColumn = document.querySelector(`.load-column[data-load-number="${loadNumber}"]`);
        var selectedType = document.querySelector(`input[name="type-${loadNumber}"]:checked`);
        var basePrice = parseFloat(selectedType.dataset.price);

        loadColumn.dataset.loadPrice = basePrice;

        updateLoadPrice(loadNumber);
    }

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

        // Calculate the total load price (base + additional)
        var totalLoadPrice = basePrice + additionalPrice;

        // Update the display of the total load price
        document.getElementById(`totalLoadPrice-${loadNumber}`).textContent = `Total Load Price: P${totalLoadPrice.toFixed(2)}`;

        updateTotalPrice(); // Update the total price whenever there's a change in any load price
    }

    function updateTotalPrice() {
        var totalPrice = 0;
        var allLoadColumns = document.querySelectorAll('.load-column');
        allLoadColumns.forEach(function(column) {
            var columnBasePrice = parseFloat(column.dataset.loadPrice);
            var columnAdditionalPrice = parseFloat(column.dataset.additionalPrice);
            totalPrice += (columnBasePrice + columnAdditionalPrice);
        });

        // Display the updated total price
        document.getElementById('totalPrice').textContent = `Total Price: P${totalPrice.toFixed(2)}`;
    }

    document.getElementById('saveButton').addEventListener('click', function() {
        document.getElementById('customerDetailsForm').style.display = 'block';
    });

    document.getElementById('customerSelect').addEventListener('change', function() {
        var selectedCustomerId = this.value;
        
        if (selectedCustomerId) {
            var selectedOption = this.options[this.selectedIndex];
            var customerDetails = selectedOption.text.match(/(.*) \((.*)\)$/);
            
            document.getElementById('firstName').value = customerDetails[1].split(' ')[0];
            document.getElementById('lastName').value = customerDetails[1].split(' ')[1];
            document.getElementById('email').value = customerDetails[2];
            document.getElementById('phone').value = selectedOption.dataset.number; // Assuming you also store the phone number in a data attribute
        } else {
            // Clear fields if no customer is selected
            document.getElementById('firstName').value = '';
            document.getElementById('lastName').value = '';
            document.getElementById('email').value = '';
            document.getElementById('phone').value = '';
        }
    });

</script>

</body>
</html>
