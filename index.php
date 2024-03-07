<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- icon -->
    <link rel="icon" href="assets/logo/logo.png">
    <!-- css and script -->
    <link rel="stylesheet" href="css/index.css">
    <script src="js/index.js"></script>
</head>
<body>
    <!-- container -->
    <div class="form--img-wrapper">
        <!-- all wrapper -->
        <div class="all-wrapper">
            <!-- img bg -->
            <div class="img-wrapper">
                <img src="assets/logo/logo.png" alt="logo" class="logo-icon">
            </div>
            <!-- section login form -->
            <div class="section-wrapper">
                <section class="login-container">
                    <h1 class="h1-login">Inventory System</h1>
                    <div class="input--label-wrapper">
                        <label for="username" class="input-label">Username</label>
                        <input type="text" class="input" id="username">
                    </div>
                    <div class="input--label-wrapper">
                        <label for="password" class="input-label">Password</label>
                        <input type="password" class="input" id="password">
                    </div>
                    <div class="show-hide-pass-wrapper">
                        <input type="checkbox" class="input-show-hide-password">
                        <span class="span-show-pass">Show Password</span>
                    </div>
                    <button class="btn-login" id="btnLogin">Login</button>
                </section>
            </div>
        </div>
    </div>
</body>
</html>