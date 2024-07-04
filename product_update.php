<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product Page</title>

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> 

    <!-- CSS File Link -->
    <link rel="stylesheet" href="css/styles2.css">
</head>
<body>

<?php

if(isset($message)){
    foreach($message as $message){
        echo '<span class="message">'.$message.'</span>';
    }
}

?>

<div class="container">

    <div class="admin-product-form-container centered">

        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
            <h3>Update Laundry Product</h3>
            <!-- <label for="product_name">Product Name:</label> -->
            <input type="text" placeholder="Enter Product Name" name="product_name" class="box"></br>
            <!-- <label for="product_price">Price</label> -->
            <input type="text" placeholder="Enter Price" name="product_price" class="box"></br>
            <input type="file" accept="image/png, image/jpeg, image/jpg" name="product_image" class="box">
            <input type="submit" class="btn" name="update_product" value="update product">
            <a href="admin_page.php" class="btn">go back</a>
        </form>

    </div>

</div>

?>
    
</body>
</html>