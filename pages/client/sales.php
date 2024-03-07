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
    <link rel="stylesheet" href="../../css/client/sales.css">
    <script src="../../js/client/sales.js" type="module"></script>
</head>
<body>
    <!-- blur -->
    <div class="blur"></div>
    <!-- header -->
    <header class="header">
        <section class="section-nav">
            <img src="../../assets/logo/logo.png" alt="logo" class="img-logo">
            <h1 class="h1-esquina">Esquina Pharmacy</h1>
            <div class="nav-item-wrapper">
                <div class="link--icon-wrapper active-link">
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
                <div class="link--icon-wrapper">
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
                <div class="link--icon-wrapper active-link">
                    <img src="../../assets/home.png" alt="home" class="nav-link-icon">
                    <a href="home.php" class="nav-item">Home</a>
                </div>
                <!-- <div class="link--icon-wrapper">
                    <img src="../../assets/information.png" alt="home" class="nav-link-icon">
                    <a href="#" class="nav-item">About Us</a>
                </div> -->
                <!-- <div class="link--icon-wrapper">
                    <img src="../../assets/folder.png" alt="home" class="nav-link-icon">
                    <a href="tableData.php" class="nav-item">Data</a>
                </div> -->
                <!-- <div class="link--icon-wrapper">
                    <img src="../assets/folder.png" alt="home" class="nav-link-icon">
                    <a href="tableData.php" class="nav-item">Reorder</a>
                </div> -->
                <div class="link--icon-wrapper">
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
        <!-- prompt -->
        <section class="section-prompt" id="editAdminPrompt">
            <h1 class="h1-sec-pin">Enter Security Pin</h1>
            <input type="password" class="input-prompt" id="editAdminProfilePromptInput">
            <div class="btn-wrapper">
                <button class="btn-prompt btn-submit-prompt" id="btnSubmitAdminProfile">Submit</button>
                <button class="btn-prompt btn-cancel-edit" id="cancelBtnAdminProfile">Cancel</button>
            </div>
        </section>
        <!-- list of items added to cart -->
        <div class="list-item-wrapper" style="display: none;">
            <div class="h1-close-btn-wrapper">
                <h1 class="h1-header-table-item-list"> Added Item List</h1>
                <span class="span-close-btn">Close</span>
            </div>
            <div class="table-item-list-wrapper">
                <table class="table-item-list">
                    <thead class="thead-item-list">
                        <th class="th-item-list">Date Receive</th>
                        <th class="th-item-list">Brand Name</th>
                        <th class="th-item-list">Generic Name</th>
                        <th class="th-item-list">Stock On Hand</th>
                        <th class="th-item-list">Price</th>
                        <th class="th-item-list">Quantity</th>
                    </thead>
                        <tbody class="tbody-item-list">
                            <!-- this is where the data will be display -->
                        </tbody>
                    <tfoot>
                        <tr>
                            <td class="td-footer-item-list" colspan="5">Total</td>
                            <td class="td-footer-item-list" id="totalPrice"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- btn checkout -->
            <button class="btn-checkout">
                <img src="../../assets/checkout.png" alt="checkout" class="img-btn-checkout">
                Checkout
            </button>
        </div>
        
        <!-- search and add btn wrapper -->
        <div class="addbtn-search-wrapper">
            <!-- search and add btn wrapper -->
            <div class="addbtn-search-wrapper">
                <!-- search wrapper -->
                <div class="search-wrapper">
                    <!-- option for search -->
                    <span class="span-search-text">Search</span>
                    <select id="searchType">
                        <option value=""></option>
                        <option value="brand_name">Brand Name</option>
                        <option value="generic_name">Generic Name</option>
                    </select>
                    <input type="text" id="inputSearch" class="input-search">
                </div>
                <!-- added item list clicker -->
                <div class="added-item-list-wrapper">
                    <img src="../../assets/cart.png" alt="item-list" class="img-list-cart">
                    <span class="span-item-list">Item List</span>
                </div>
            </div>
        </div>
        <!-- section for table -->
        <section class="section-table-wrapper">
        </section>
        <!-- add to cart -->    
        <div class="img-cart-cart-span-wrapper">
            <img src="../../assets/cart.png" alt="cart" class="img-add-to-cart">
            <span class="span-cart">ADD TO CART</span>
        </div>
        <!-- daily sales -->
        <section class="daily-sales-container">
            <div class="daily-sales-inside-wrapper">
                <div class="h1-select_date-wrapper">
                    <h1 class="h1-daily-sales">Daily Sales</h1>
                    <input type="date" id="dailySalesPickDate">
                </div>
                <div class="table-wrapper-daily-sales">
                    <!-- table -->
                    <table class="table-daily-sales">
                        <thead class="thead-daily-sales">
                            <th class="th-daily-sales">Brand Name</th>
                            <th class="th-daily-sales">Generic Name</th>
                            <th class="th-daily-sales">Price</th>
                            <th class="th-daily-sales">Quantity</th>
                            <th class="th-daily-sales">Total Price</th>
                            <th class="th-daily-sales">Seller</th>
                        </thead>
                        <tbody class="tbody-daily-sales">
                            <!-- this is where the daily sales will be dynamically added to the tbl -->
                        </tbody>
                        <tfoot class="tfoot-daily-sales">
                            <tr class="t-foot-tr-daily-sales">
                                <td colspan="4" class="t-foot-daily-sales-td">Total</td>
                                <td class="t-foot-daily-sales-td" id="totalDailySales">234 Php</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </section>
    </main>
    <!-- footer -->
    <footer class="footer">
        <!-- <span class="footer-copy-right">&#169;</span> -->
    </footer>
</body>
</html>