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
    <link rel="stylesheet" href="../css/user.css">
    <script src="../js/user.js" type="module"></script>
</head>
<body>
    <!-- blur -->
    <div class="blur"></div>
    <!-- form prompt -->
    <!-- EDITADMIN PROFILE -->
    <section class="section-prompt" id="editAdminPrompt">
        <h1 class="h1-sec-pin">Enter Security Pin</h1>
        <input type="password" class="input-prompt" id="editAdminProfilePromptInput">
        <div class="btn-wrapper">
            <button class="btn-prompt btn-submit-prompt" id="btnSubmitAdminProfile">Submit</button>
            <button class="btn-prompt btn-cancel-edit" id="cancelBtnAdminProfile">Cancel</button>
        </div>
    </section>
    <!-- DELETE SALESMAN ACC -->
    <section class="section-prompt" id="deleteSalesmanPrompt">
        <h1 class="h1-sec-pin">Enter Security Pin</h1>
        <input type="password" class="input-prompt" id="deleteSalesmanAccountPromptInput">
        <div class="btn-wrapper">
            <button class="btn-prompt btn-submit-prompt" id="btnSubmitDeleteSalesmanAcc">Submit</button>
            <button class="btn-prompt btn-cancel-edit" id="cancelBtnDeleteAcc">Cancel</button>
        </div>
    </section>
    <!-- EDIT SALESMAN ACC -->
    <section class="section-prompt" id="editSalesmanPrompt">
        <h1 class="h1-sec-pin">Enter Security Pin</h1>
        <input type="password" class="input-prompt" id="editSalesmanPromptInput">
        <div class="btn-wrapper">
            <button class="btn-prompt btn-submit-prompt" id="submitBtnEditSalesmanPrompt">Submit</button>
            <button class="btn-prompt btn-cancel-edit" id="cancelBtnEditSalesmanAcc">Cancel</button>
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
                <div class="link--icon-wrapper">
                    <img src="../assets/folder.png" alt="home" class="nav-link-icon">
                    <a href="tableData.php" class="nav-item">Data</a>
                </div>
                <!-- <div class="link--icon-wrapper">
                    <img src="../assets/folder.png" alt="home" class="nav-link-icon">
                    <a href="tableData.php" class="nav-item">Reorder</a>
                </div> -->
                <div class="link--icon-wrapper active-link">
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
        <!-- admin profile -->
        <section class="section-profile">   
            <!-- profile -->
            <section class="profile-wrapper">
                <!-- form -->
                <div class="my-acc-wrapper">
                    <h1 class="my-profile-h1" style="font-weight: bold;">My Profile</h1>
                    <form class="profile-details">
                        <div class="edit-btn--h1-wrapper-1">
                            <h1 class="h1-your-details" style="font-weight: bold;">Your Details</h1>
                            <img src="../../assets/edit.png" alt="edit" class="img-edit-icon" title="Edit Profile">
                        </div>
                        <!-- inputs -->
                        <span class="span-details">Name</span>
                        <input type="text" class="input-profile" id="name" readonly>
                        <span class="span-details">Username</span>
                        <input type="text" class="input-profile" id="uname" readonly>
                        <span class="span-details">Password</span>
                        <input type="password" class="input-profile" id="pass" readonly>
                        <span class="span-details">Security Pin</span>
                        <input type="password" class="input-profile" id="secPin" readonly>
                        <!-- btn -->
                        <div class="btn-changes-wrapper">
                            <button class="btn btn-save" disabled>Save</button>
                            <button class="btn btn-cancel">Cancel</button>
                        </div>
                    </form>
                </div>
                <!-- create account form -->
                <div class="create-acc-wrapper">
                    <h1 class="h1-create-acc" style="font-weight: bold;">Create An Account</h1>
                    <form class="form-create-acc">
                        <h1 class="h1-create-acc-acc-details" style="font-weight: bold;">Account Details</h1>
                        <label for="createAccName" class="create-acc-label-input">Name</label>
                        <div class="create-acc-label-input-wrapper">
                            <input type="text" class="create-acc-input-name" id="createAccName">
                        </div>
                        <label for="createAccName" class="create-acc-label-input">Username</label>
                        <div class="create-acc-label-input-wrapper">
                            <input type="text" class="create-acc-input-name" id="createAccUName">
                        </div>
                        <label for="createAccName" class="create-acc-label-input">Password</label>
                        <div class="create-acc-label-input-wrapper">
                            <input type="password" class="create-acc-input-name" id="createAccPassword">
                        </div>
                        <label for="createAccName" class="create-acc-label-input">Security Pin</label>
                        <div class="create-acc-label-input-wrapper">
                            <input type="password" class="create-acc-input-name" id="createAccSecPin">
                        </div>
                        <button class="btn-create">Create</button>
                    </form>
                </div>
            </section>
            <!-- account manager -->
            <div class="manage-account-wrapper">
                <h1 class="h1-manage-account">Manage Account</h1>
                <div class="table-wrapper">
                    <table class="table">
                        <thead class="thead">
                            <th class="th">Full Name</th>
                            <th class="th">Username</th>
                            <th class="th">Password</th>
                            <th class="th">Security Pin</th>
                            <th class="th">Time Registered</th>
                            <th class="th">Action</th>
                        </thead>
                        <tbody class="tbody">
                            <!-- this is where the table data will display -->
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        <!-- SECTION FOR EDITING THE SALESMAN PROFILE -->
        <section class="section-profile-editor">
            <form class="edit-profile-form">
                <h1 class="h1-edit-form">Edit Profile</h1>
                <input type="hidden" id="salesmanHiddenId">
                <div class="edit-input-label-wrapper">
                    <label for="editFullname" class="edt-lbl">Fullname</label>
                    <input type="text" class="edit-input" id="editFullname">
                </div>
                <div class="edit-input-label-wrapper">
                    <label for="editUsername" class="edt-lbl">Username</label>
                    <input type="text" class="edit-input" id="editUsername">
                </div>
                <div class="edit-input-label-wrapper">
                    <label for="editPass" class="edt-lbl">Password</label>
                    <input type="text" class="edit-input" id="editPass">
                </div>
                <div class="edit-input-label-wrapper">
                    <label for="editSecPin" class="edt-lbl">Security Pin</label>
                    <input type="password" class="edit-input" id="editSecPin">
                </div>
                <div class="btn-wrapper">
                    <button class="btn btn-edit" id="btnEditSalesmanProf">Edit</button>
                    <button class="btn btn-cancel">Cancel</button>
                </div>
            </form>
        </section>
    </main>
    <!-- footer -->
    <footer class="footer"></footer>
</body>
</html>