<?php
    session_start();
    if(!isset($_SESSION['hasLog'])){
        if($_SESSION['position'] != 'admin'){
            header('location: ../index.php');
            exit;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table Data</title>
    <!-- icon -->
    <link rel="icon" href="../assets/logo/logo.png">
    <!-- css & script -->
    <link rel="stylesheet" href="../css/reorder.css">
    <script src="../js/reorder.js" type="module"></script>
</head>
<body>
    <!-- header -->
    <header class="header">
        <section class="section-nav">
            <img src="../assets/logo/logo.png" alt="logo" class="img-logo">
            <div class="nav-item-wrapper">
                <div class="link--icon-wrapper">
                    <img src="../assets/home.png" alt="home" class="nav-link-icon">
                    <a href="home.php" class="nav-item">Home</a>
                </div>
                <div class="link--icon-wrapper">
                    <img src="../assets/information.png" alt="home" class="nav-link-icon">
                    <a href="#" class="nav-item">About Us</a>
                </div>
                <div class="link--icon-wrapper">
                    <img src="../assets/folder.png" alt="home" class="nav-link-icon">
                    <a href="tableData.php" class="nav-item">Data</a>
                </div>
                <div class="link--icon-wrapper active-link">
                    <img src="../assets/folder.png" alt="home" class="nav-link-icon">
                    <a href="tableData.php" class="nav-item">Reorder</a>
                </div>
                <div class="link--icon-wrapper">
                    <img src="../assets/user.png" alt="home" class="nav-link-icon">
                    <a href="user.php" class="nav-item">User</a>
                </div>
                <div class="link--icon-wrapper">
                    <img src="../assets/logout.png" alt="home" class="nav-link-icon">
                    <a href="logout.php" class="nav-item">Sign Out</a>
                </div>
            </div>
        </section>
    </header>
    <!-- header -->
    <!-- main -->
    <main class="main">
        <div class="main-container">
            <div class="table-container">
                <table class="table">
                    <thead class="thead">
                        <th class="th">Id</th>
                        <th class="th">Date Received</th>
                        <th class="th">Brand Name</th>
                        <th class="th">Generic Name</th>
                        <th class="th">Dosage</th>
                        <th class="th">Stock Received</th>
                        <th class="th">Stock On Hand</th>
                        <th class="th">LOT No.</th>
                        <th class="th">Expiry Date</th>
                        <th class="th">Action</th>
                    </thead>
                    <tbody id="tbody">
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <!-- footer -->
    <footer class="footer"></footer>
    
</body>
</html>