<?php
    session_start();
    if(!isset($_SESSION['hasLog'])){
        if($_SESSION['position'] != 'sale_man'){
            header('location: ../../index.php');
            exit;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
    <h1>Welcome User</h1>
</body>
</html>