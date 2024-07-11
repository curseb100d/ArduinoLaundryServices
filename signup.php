<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>
<body>
    <?php
        require('./config.php');
        if(isset($_POST['signup_button'])) {
            $firstname=$_POST['firstname'];
            $lastname=$_POST['lastname'];
            $email=$_POST['email'];
            $password=$_POST['password'];
            $confPassword=$_POST['confPassword'];
            $p=arduino::connect()->prepare('INSERT INTO adminuser(firstname, lastname, email, password) VALUES(:f, :l, :e, :p)');
            $p->bindValue(':f', $firstname);
            $p->bindValue(':l', $lastname);
            $p->bindValue(':e', $email);
            $p->bindValue(':p', $password);
            $p->execute();

        }
    ?>
    <div class="form">
        <div class="title">
            <p>Sign Up Form</p>
        </div>
        <form action="" method="POST">
            <input type="text" name="firstname" placeholder="Enter First Name">
            <input type="text" name="lastname" placeholder="Enter Last Name">
            <input type="text" name="email" placeholder="Enter Email">
            <input type="text" name="password" placeholder="Enter Password">
            <input type="text" name="confPassword" placeholder="Confirm Password">
            <input type="submit" value="Sign Up" name="signup_button">
        </form>
    </div>
</body>
</html>