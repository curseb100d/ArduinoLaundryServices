<?php 

$conn = mysqli_connect('localhost','root','','arduinolaundry_db');

    class arduino{
        public static function connect()
        {
            try {
                $conn = new PDO('mysql:localhost=host; dbname=arduinolaundry_db', 'root', '');
                return $conn;
            } catch(PDOException $error1) {
                echo 'Theres a Problem'.$error1->getMessage();
            } catch(Exception $error2) {
                echo 'Connection Error!'.$error2->getMessage();
            }
        }
    }

?>