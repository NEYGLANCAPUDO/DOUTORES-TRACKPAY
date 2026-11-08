<?php

function conn(): mysqli
{
    $conn = new mysqli('127.0.0.1', 'root', '', 'trackpay', 3306);
    $conn->set_charset('utf8mb4');

    return $conn;
}