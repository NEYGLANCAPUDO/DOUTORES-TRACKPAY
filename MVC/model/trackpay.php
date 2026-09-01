<?php
function conn(){
    $conn = mysqli_connect("localhost","root","","trackpay");
    if(!$conn){
        die(mysqli_connect_errno());
    }
    return $conn;
}


?>