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
    <!-- icon -->
    <link rel="icon" href="../../assets/logo/logo.png">
    <!-- css & script -->
    <link rel="stylesheet" href="../../css/client/user.css">
    <script src="../../js/client/user.js" type="module"></script>
</head>
<body>
    <!-- blur -->
    <div class="blur"></div>
    <!-- form prompt -->
    <section class="section-prompt">
        <h1 class="h1-sec-pin">Enter Security Pin</h1>
        <div class="div-hidden-wrapper">
            <label for="hiddenSample" id="labelSecPinHide">Security Pin</label>
            <input type="number" id="hiddenSample" class="input-prompt" max="9999">
            <input type="text" id="catcher" class="input-prompt" readonly>
        </div>
        <div class="btn-wrapper">
            <button class="btn-prompt btn-submit-prompt">Submit</button>
            <button class="btn-prompt btn-cancel-edit">Cancel</button>
        </div>
    </section>
    <!-- header -->
    <header class="header">
        <section class="section-nav">
            <img src="../../assets/logo/logo.png" alt="logo" class="img-logo">
            <h1 class="h1-esquina">Esquina Pharmacy</h1>
            <div class="nav-item-wrapper">
                <div class="link--icon-wrapper">
                    <img src="../../assets/home.png" alt="home" class="nav-link-icon">
                    <a href="sales.php" class="nav-item">Home</a>
                </div>
                <!-- <div class="link--icon-wrapper">
                    <img src="../../assets/information.png" alt="home" class="nav-link-icon">
                    <a href="#" class="nav-item">About Us</a>
                </div> -->
                <!-- <div class="link--icon-wrapper">
                    <img src="../../assets/folder.png" alt="home" class="nav-link-icon">
                    <a href="#" class="nav-item">Data</a>
                </div> -->
                <div class="link--icon-wrapper active-link">
                    <img src="../../assets/user.png" alt="home" class="nav-link-icon">
                    <a href="user.php" class="nav-item">User</a>
                </div>
                <div class="link--icon-wrapper">
                    <img src="../../assets/logout.png" alt="home" class="nav-link-icon">
                    <a href="../logout.php" class="nav-item">Sign Out</a>
                </div>
            </div>
            <!-- responsive nav -->
            <nav id="nav">
                <div class="link--icon-wrapper">
                    <img src="../../assets/home.png" alt="home" class="nav-link-icon">
                    <a href="home.php" class="nav-item">Home</a>
                </div>
                <!-- <div class="link--icon-wrapper">
                    <img src="../../assets/information.png" alt="home" class="nav-link-icon">
                    <a href="#" class="nav-item">About Us</a>
                </div>
                <div class="link--icon-wrapper">
                    <img src="../../assets/folder.png" alt="home" class="nav-link-icon">
                    <a href="tableData.php" class="nav-item">Data</a>
                </div> -->
                <!-- <div class="link--icon-wrapper">
                    <img src="../assets/folder.png" alt="home" class="nav-link-icon">
                    <a href="tableData.php" class="nav-item">Reorder</a>
                </div> -->
                <div class="link--icon-wrapper active-link">
                    <img src="../../assets/user.png" alt="home" class="nav-link-icon">
                    <a href="user.php" class="nav-item">User</a>
                </div>
                <div class="link--icon-wrapper">
                    <img src="../../assets/logout.png" alt="home" class="nav-link-icon">
                    <a href="../logout.php" class="nav-item">Sign Out</a>
                </div>
                <div class="link--icon-wrapper" id="closeResponsiveNav">
                    <img src="../../assets/exit.png" alt="home" class="nav-link-icon">
                    <span href="#" class="nav-item">Exit</span>
                </div>
            </nav>
            <!-- burger menu -->
            <img src="../../assets/burger.png" alt="burger-menu" class="img-burger-menu">
        </section>
    </header>
    <!-- main -->
    <main class="main">
        <!-- profile -->
        <section class="profile-wrapper">
            <h1 class="my-profile-h1">My Profile</h1>
            <!-- form -->
            <form class="profile-details">
                <div class="edit-btn--h1-wrapper">
                    <h1 class="h1-your-details">Your Details</h1>
                    <img src="../../assets/edit.png" alt="edit" class="img-edit-icon" title="Edit Profile">
                </div>
                <!-- inputs -->
                <span class="span-details">Name</span>
                <input type="text" class="input-profile" id="name" readonly>
                <span class="span-details">Username</span>
                <input type="text" class="input-profile" id="uname" readonly>
                <span class="span-details">Password</span>
                <input type="password" class="input-profile" id="pass" readonly>
                <span class="span-details">Pin</span>
                <input type="password" class="input-profile" id="secPin" readonly>
                <!-- btn -->
                <div class="btn-changes-wrapper">
                    <button class="btn btn-save" disabled>Save</button>
                    <button class="btn btn-cancel">Cancel</button>
                </div>
            </form>
        </section>
    </main>
    <!-- footer -->
    <footer class="footer">
    </footer>
</body>
</html>