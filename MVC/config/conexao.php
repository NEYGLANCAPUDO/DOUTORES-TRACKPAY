<?php

function conn(){
$conn = mysqli_connect('localhost', 'root', '', 'trackpay');
if(!$conn){
    die('ERRO AO CONECTAR'. mysqli_connect_error());
}
return $conn;
}

?>
