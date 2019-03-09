<?php
$servername = "localhost";
$username = "root";
$password = "5636763";
$db = '`camagru`';
$conn = mysqli_connect($servername, $username, $password);
mysqli_query($conn, "CREATE DATABASE " . $db);
mysqli_query($conn, "USE " . $db);
$sql = explode(';', file_get_contents(ROOT. '/config/setup.sql'));
foreach ($sql as $item) {
    if (!empty($item)) {
        mysqli_query($conn, $item);
    }
}