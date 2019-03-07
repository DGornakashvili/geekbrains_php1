<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign up</title>
    {{STYLE}}
</head>
<body>
<div class="signUp-form">
    <form class="form-form" action="" method="POST">
        <label>Name<input type="text" name="name" required minlength="3"></label>
        <label>Login<input type="text" name="login" required minlength="3"></label>
        <label>Password<input type="password" name="password" required minlength="3"></label>
        <button class="signUp-btn">Sign up</button>
    </form>
</div>
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/product.js"></script>
</body>
</html>