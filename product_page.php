<?php

@include 'config.php';

if(isset($_POST['add_product'])){

    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_FILES['product_image']['name'];
    $product_image_tmp_name = $_FILES['product_image']['tmp_name'];
    $product_image_folder = 'uploaded_images/'.$product_image;
    
    if(empty($product_name) || empty($product_price) || empty($product_image)){
        $message[] = 'please fill out all';
    }else{
        $insert = "INSERT INTO laundry_products(name, price, image) VALUES('$product_name', '$product_price', '$product_image')";
        $upload = mysqli_query($conn, $insert);
        if($upload){
            move_uploaded_file($product_image_tmp_name, $product_image_folder);
            $message[] = 'new product for laundry added';
        }else{
            $message[] = 'cannot add a laundry product';
        }
    }
}

?>

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

<?php

if(isset($message)){
    foreach($message as $message){
        echo '<span class="message">'.$message.'</span>';
    }
}

?>

<div class="container">

    <div class="admin-product-form-container">

        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
            <h3>New Laundry Product</h3>
            <!-- <label for="product_name">Product Name:</label> -->
            <input type="text" placeholder="Enter Product Name" name="product_name" class="box"></br>
            <!-- <label for="product_price">Price</label> -->
            <input type="text" placeholder="Enter Price" name="product_price" class="box"></br>
            <input type="file" accept="image/png, image/jpeg, image/jpg" name="product_image" class="box">
            <input type="submit" class="btn" name="add_product" value="add product">
        </form>

    </div>

    <?php
    
        $select = mysqli_query($conn, "SELECT * FROM laundry_products");

    ?>

    <div class="product-display">

        <table class="product-display-table">

            <thead>
                <tr>
                    <th>product image</th>
                    <th>product name</th>
                    <th>product price</th>
                    <th>action</th>
                </tr>
            </thead>

            <?php while($row = mysqli_fetch_assoc($select)){ ?>

            <tr>
                <td><img src="uploaded_images/<?php echo $row['image']; ?>" height="100" alt=""></td>
                <td><?php echo $row['name']; ?></td>
                <td>P<?php echo $row['price']; ?></td>
                <td>
                    <a href="product_update.php?edit=<?php echo $row['id']; ?>" class="btn"> <i class="fas fa-edit"></i> edit </a>
                    <a href="product_page.php?delete=<?php echo $row['id']; ?>" class="btn"> <i class="fas fa-trash"></i> delete </a>
                </td>
            </tr>

            <?php }; ?>

        </table>

    </div>

</div>
</body>
</html>