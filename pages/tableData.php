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
    <link rel="stylesheet" href="../css/tableData.css">
    <script src="../js/tableData.js" type="module"></script>
</head>
<body>
    <!-- EDIT SALESMAN ACC -->
    <section class="section-prompt" id="editSalesmanPrompt">
        <h1 class="h1-sec-pin">Enter Security Pin</h1>
        <input type="password" class="input-prompt" id="editSalesmanPromptInput">
        <div class="btn-wrapper">
            <button class="btn-prompt btn-submit-prompt" id="submitBtnEditSalesmanPrompt">Submit</button>
            <button class="btn-prompt btn-cancel-edit" id="cancelBtnEditSalesmanAcc">Cancel</button>
        </div>
    </section>
    <!-- blur -->
    <div class="blur"></div>
    <!-- section for adding item -->
    <section class="add-item-form">
        <div class="h1-close-btn-wrapper">
            <h1 class="add-item-h1">Add New Item</h1>
            <img src="../assets/close.png" alt="" class="img-close-add-form">
        </div>
        <div class="input-label-wrapper">
            <label for="brandName" class="input-label">Brand Name</label>
            <input type="text" id="brandName" class="input-item">
        </div>
        <div class="input-label-wrapper">
            <label for="genericName" class="input-label">Generic Name</label>
            <input type="text" id="genericName" class="input-item">
        </div>
        <div class="input-label-wrapper">
            <label for="dosage" class="input-label">Dosage</label>
            <input type="text" id="dosage" class="input-item">
        </div>
        <div class="input-label-wrapper">
            <label for="stockRecieved" class="input-label">Stock Recieved</label>
            <input type="number" id="stockRecieved" class="input-item" step="any">
        </div>
        <div class="input-label-wrapper">
            <label for="threshold" class="input-label">Threshold</label>
            <input type="number" id="threshold" class="input-item" step="any">
        </div>
        <div class="input-label-wrapper">
            <label for="lotNo" class="input-label">LOT No.</label>
            <input type="text" id="lotNo" class="input-item">
        </div>
        <div class="input-label-wrapper">
            <label for="expiryDate" class="input-label">Expiry Date</label>
            <input type="date" id="expiryDate" class="input-item">
        </div>
        <div class="input-label-wrapper">
            <label for="price" class="input-label">Price</label>
            <input type="number" id="price" class="input-item" step="any">
        </div>
        <div class="input-label-wrapper">
            <label for="dateRecieved" class="input-label">Date Recieved</label>
            <input type="date" id="dateRecieved" class="input-item">
        </div>
        <div class="input-label-wrapper">
            <label for="retailPrice" class="input-label">Retail Price</label>
            <input type="number" id="retailPrice" class="input-item" step="any">
        </div>
        <div class="input-label-wrapper">
            <label for="prescription" class="input-label">Prescription</label>
            <select class="input-item" id="prescription">
                <option value="yes">Yes</option>
                <option value="yes">No</option>
            </select>
        </div>
        <div class="btn-wrapper">
            <button class="btn btn-submit" id="addItemBtn">Add</button>
            <button class="btn btn-clear">Clear</button>
        </div>
    </section>
    <!-- section edit item -->
    <section class="edit-item-form">
        <div class="edit-h1-close-btn-wrapper">
            <h1 class="edit-item-h1">Edit Item</h1>
            <img src="../assets/close.png" alt="logo" class="img-close-edit-form">
        </div>
        <input type="hidden" id="itemId">
        <div class="edit-input-label-wrapper">
            <label for="editBrandName" class="edit-input-label">Brand Name</label>
            <input type="text" id="editBrandName" class="edit-input-item">
        </div>
        <div class="input-label-wrapper">
            <label for="editgenericName" class="edit-input-label">Generic Name</label>
            <input type="text" id="editgenericName" class="edit-input-item">
        </div>
        <div class="input-label-wrapper">
            <label for="editdosage" class="edit-input-label">Dosage</label>
            <input type="text" id="editdosage" class="edit-input-item">
        </div>
        <div class="input-label-wrapper">
            <label for="editstockRecieved" class="edit-input-label">Stock Recieved</label>
            <input type="number" id="editstockRecieved" class="edit-input-item" step="any">
        </div>
        <div class="input-label-wrapper">
            <label for="editstockOnHand" class="edit-input-label">Stock On Hand</label>
            <input type="number" id="editstockOnHand" class="edit-input-item" step="any">
        </div>
        <div class="input-label-wrapper">
            <label for="editlotNo" class="edit-input-label">LOT No.</label>
            <input type="text" id="editlotNo" class="edit-input-item">
        </div>
        <div class="input-label-wrapper">
            <label for="editexpiryDate" class="edit-input-label">Expiry Date</label>
            <input type="date" id="editexpiryDate" class="edit-input-item">
        </div>
        <div class="input-label-wrapper">
            <label for="editprice" class="edit-input-label">Price</label>
            <input type="number" id="editprice" class="edit-input-item" step="any">
        </div>
        <div class="input-label-wrapper">
            <label for="editdateRecieved" class="edit-input-label">Date Recieved</label>
            <input type="date" id="editdateRecieved" class="edit-input-item">
        </div>
        <div class="input-label-wrapper">
            <label for="editretailPrice" class="edit-input-label">Retail Price</label>
            <input type="number" id="editretailPrice" class="edit-input-item" step="any">
        </div>
        <div class="input-label-wrapper">
            <label for="editPrescription" class="edit-input-label">Prescription</label>
            <select class="edit-input-item" id="editPrescription">
                <option value="yes">Yes</option>
                <option value="no">No</option>
            </select>
        </div>
        <div class="edit-btn-wrapper">
            <button class="btn btn-submit-edit" id="editItemBtn">Edit</button>
        </div>
    </section>
    <!-- header -->
    <header class="header">
        <section class="section-nav">
            <img src="../assets/logo/logo.png" alt="logo" class="img-logo">
            <h1 class="h1-esquina">Esquina Pharmacy</h1>
            <!-- 1st nav -->
            <div class="nav-item-wrapper">
                <div class="link--icon-wrapper">
                    <img src="../assets/home.png" alt="home" class="nav-link-icon">
                    <a href="home.php" class="nav-item">Home</a>
                </div>
                <!-- <div class="link--icon-wrapper">
                    <img src="../assets/information.png" alt="home" class="nav-link-icon">
                    <a href="#" class="nav-item">About Us</a>
                </div> -->
                <div class="link--icon-wrapper active-link">
                    <img src="../assets/folder.png" alt="home" class="nav-link-icon">
                    <a href="tableData.php" class="nav-item">Data</a>
                </div>
                <!-- <div class="link--icon-wrapper">
                    <img src="../assets/folder.png" alt="home" class="nav-link-icon">
                    <a href="tableData.php" class="nav-item">Reorder</a>
                </div> -->
                <div class="link--icon-wrapper">
                    <img src="../assets/user.png" alt="home" class="nav-link-icon">
                    <a href="user.php" class="nav-item">User</a>
                </div>
                <div class="link--icon-wrapper">
                    <img src="../assets/logout.png" alt="home" class="nav-link-icon">
                    <a href="logout.php" class="nav-item">Sign Out</a>
                </div>
            </div>
            <!-- responsive nav -->
            <nav class="nav">
                <div class="link--icon-wrapper active-link">
                    <img src="../assets/home.png" alt="home" class="nav-link-icon">
                    <a href="home.php" class="nav-item">Home</a>
                </div>
                <!-- <div class="link--icon-wrapper">
                    <img src="../assets/information.png" alt="home" class="nav-link-icon">
                    <a href="#" class="nav-item">About Us</a>
                </div> -->
                <div class="link--icon-wrapper">
                    <img src="../assets/folder.png" alt="home" class="nav-link-icon">
                    <a href="tableData.php" class="nav-item">Data</a>
                </div>
                <!-- <div class="link--icon-wrapper">
                    <img src="../assets/folder.png" alt="home" class="nav-link-icon">
                    <a href="tableData.php" class="nav-item">Reorder</a>
                </div> -->
                <div class="link--icon-wrapper">
                    <img src="../assets/user.png" alt="home" class="nav-link-icon">
                    <a href="user.php" class="nav-item">User</a>
                </div>
                <div class="link--icon-wrapper">
                    <img src="../assets/logout.png" alt="home" class="nav-link-icon">
                    <a href="logout.php" class="nav-item">Sign Out</a>
                </div>
                <!-- <div class="link--icon-wrapper">
                    <img src="../assets/exit.png" alt="home" class="nav-link-icon">
                    <a href="$" class="nav-item">Exit</a>
                </div> -->
            </nav>
            <img src="../assets/burger.png" alt="burger-menu" class="img-burger-menu">
        </section>
    </header>
    <!-- main -->
    <main class="main">
        <!-- search and add btn wrapper -->
        <div class="addbtn-search-wrapper">
            <!-- button add item -->
            <button class="btn-add-item" title="add item">
                <img src="../assets/logo/plus.png" alt="add-logo" class="img-add-icon">
            </button>
            <!-- search wrapper -->
            <div class="search-wrapper">
                <!-- option for search -->
                <span class="span-search-text">Search</span>
                <select id="searchType">
                    <option value=""></option>
                    <option value="brand_name">Brand Name</option>
                    <option value="generic_name">Generic Name</option>
                    <option value="lot_no">Lot No.</option>
                    <option value="expiry_date">Expiry Date</option>
                </select>
                <input type="text" id="inputSearch" class="input-search">
            </div>
        </div>
        <!-- section for table -->
        <section class="section-table-wrapper">
            <!-- table -->
            <table class="table">
                <!-- thead -->
                <thead class="thead">
                    <th class="th">ID No.</th>
                    <th class="th">Date Receive</th>
                    <th class="th">
                        <div class="th-textContent-sort-icon-wrapper">
                            <span>Brand Name</span>
                            <input type="hidden" class="hidden_type" value="<?=htmlspecialchars('brand_name', double_encode: false);?>">
                            <img src="../assets/sort.png" class="sort-icon" title="Sort By Brand Name" data-record-id="<?=htmlspecialchars('ASC', ENT_QUOTES, 'UTF-8');?>">
                            <img src="../assets/sort.png" class="sort-icon-desc" title="Sort By Brand Name" data-record-id="DESC">
                        </div>
                    </th>
                    <th class="th">
                        <div class="th-textContent-sort-icon-wrapper">
                            <span>Generic Name</span>
                            <input type="hidden" class="hidden_type" value="<?=htmlspecialchars('generic_name', double_encode: false);?>">
                            <img src="../assets/sort.png" class="sort-icon" title="Sort By Generic Name" data-record-id="ASC">
                            <img src="../assets/sort.png" class="sort-icon-desc" title="Sort By Generic Name" data-record-id="DESC">
                        </div>
                    </th>
                    <th class="th">Dosage</th>
                    <th class="th">Stock Received</th>
                    <th class="th">
                        <div class="th-textContent-sort-icon-wrapper">
                            <span>Stock On Hand</span>
                            <input type="hidden" class="hidden_type" value="<?=htmlspecialchars('stock_on_hand', double_encode: false);?>">
                            <img src="../assets/sort.png" class="sort-icon" title="Sort By Stock On Hand" data-record-id="ASC">
                            <img src="../assets/sort.png" class="sort-icon-desc" title="Sort By Stock On Hand" data-record-id="DESC">
                        </div>
                    </th>
                    <th class="th">Threshold</th>
                    <th class="th">LOT No.</th>
                    <th class="th">
                        <div class="th-textContent-sort-icon-wrapper">
                            <span>Expiry Date</span>
                            <input type="hidden" class="hidden_type" value="<?=htmlspecialchars('expiry_date', double_encode: false);?>">
                            <img src="../assets/sort.png" class="sort-icon" title="Sort By Expiry Date" data-record-id="ASC">
                            <img src="../assets/sort.png" class="sort-icon-desc" title="Sort By Expiry Date" data-record-id="DESC">
                        </div>
                    </th>
                    <th class="th">Price</th>
                    <th class="th">Retail Price</th>
                    <th class="th">Prescription</th>
                    <th class="th">Action</th>
                </thead>
                <!-- TBODY -->
                <tbody class="tbody">
                    <!-- THIS IS THE TABLE DATA WILL BE DISPLAY -->
                </tbody>
            </table>
        </section>
        <!-- PAGANATION -->
        <div class="paganation-wrapper">
            <!-- TOTAL PAGES -->
            <div class="total-pages-wrapper">
                <h2 class="span-total-pages">Total: </h2>
                <h2 class="span-page-no"></h2>
            </div>
            <!-- PAGES MANIPULATION -->
            <div class="btn-wrapper-all">
                <button class="btn-page btn-first-page">First</button>
                <button class="btn-prev">Prev</button>
                <span class="btn-span-cur-page">0</span>
                <button class="btn-next">Next</button>
                <button class="btn-page btn-last-page">Last</button>
            </div>
        </div>
    </main>
    <!-- footer -->
    <footer class="footer">
    </footer>
</body>
</html>