<?php

@include 'config.php';

$id = $_GET['edit'];

if(isset($_POST['update_product'])){

    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_FILES['product_image']['name'];
    $product_image_tmp_name = $_FILES['product_image']['tmp_name'];
    $product_image_folder = 'uploaded_images/'.$product_image;
    
    if(empty($product_name) || empty($product_price) || empty($product_image)){
        $message[] = 'please fill out all';
    }else{
        $update = "UPDATE laundry_products SET name='$product_name', price='$product_price', image='$product_image' WHERE id = $id";
        $upload = mysqli_query($conn, $update);
        if($upload){
            move_uploaded_file($product_image_tmp_name, $product_image_folder);
            header('Location: product_page.php');
            exit;
        }else{
            $message[] = 'cannot add a laundry product';
        }
    }
};

?>

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

        <?php

        $select = mysqli_query($conn, "SELECT * FROM laundry_products WHERE id = $id");
        while($row = mysqli_fetch_assoc($select)){

        ?>

        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
            <h3>Update Laundry Product</h3>
            <!-- <label for="product_name">Product Name:</label> -->
            <input type="text" placeholder="Enter Product Name" value="<?php echo $row['name']; ?>" name="product_name" class="box"></br>
            <!-- <label for="product_price">Price</label> -->
            <input type="number" placeholder="Enter Price" value="<?php echo $row['price']; ?>" name="product_price" class="box"></br>
            <input type="file" accept="image/png, image/jpeg, image/jpg" name="product_image" class="box">
            <input type="submit" class="btn" name="update_product" value="update product">
            <a href="product_page.php" class="btn">go back</a>
        </form>

        <?php }; ?>

    </div>

</div>

?>
    
</body>
</html>