<?php

require_once __DIR__ . '/../config/config.php';

$sqlProducts = 'SELECT * FROM `products`';
$sqlCart = 'SELECT * FROM `cart`';
$sqlCss = "SELECT * FROM `css` WHERE `name` LIKE 'product'";

$sign = '';

if (empty($_SESSION['user'])) {
    $sign = "<a href='/signIn.php'>Sing in</a>\n<a href='/signUp.php'>Sing up</a>";
} else {
    $sign = '<a href="/signOut.php">Sign out</a>';
}

echo render(TEMPLATES_DIR . 'product.tpl', [
    'title' => 'Products',
    'class' => 'products',
    'sign' => $sign,
    'content' => generateProduct($sqlProducts),
    'cartcontent' => generateCart($sqlCart) ? generateCart($sqlCart): 'Cart is empty',
    'style' => generateCss($sqlCss),
]);