<?php
$servername = "localhost";
$username = "root";
$password = "5636763";
$conn = mysqli_connect($servername, $username, $password);
$sql = "CREATE DATABASE `camagru`";
mysqli_query($conn, $sql);