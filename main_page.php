<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Page</title>

    <!-- CSS File Link -->
    <link rel="stylesheet" href="css/styles2.css">
</head>
<body>
    <div class="container">
        <div class="main-page-new-laundry-container">

        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
            <h3>New Laundry</h3>
            <label for="product_name">Product Name:</label>
            <input type="text" placeholder="Enter Product Name" name="product_name" class="box"></br>
            <label for="price">Price</label>
            <input type="text" placeholder="Enter Price" name="price" class="box"></br>
            <input type="file" accept="image/png, image/jpeg, image/jpg" name="laundry_image" class="box">
            <input type="submit" class="btn" name="add_laundry" value="add laundry">
        </form>

        </div>
    </div>
</body>
</html>