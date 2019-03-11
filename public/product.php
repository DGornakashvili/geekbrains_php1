<?php

require_once __DIR__ . '/../config/config.php';

$userId = $_SESSION['user']['id'] ?? 0;
$sqlProducts = 'SELECT * FROM `products`';
$sqlCart = "SELECT * FROM `cart` WHERE `user_id`=$userId";
$sqlCss = "SELECT * FROM `css` WHERE `name` LIKE 'product'";

$sign = '';
$orderBtn = '';

if (empty($_SESSION['user'])) {
    $sign = "<a href='/signIn.php'>Sign in</a>\n<a href='/signUp.php'>Sign up</a>";
    $orderBtn = 'Sign in first!';
} else {
    $sign = '<a href="/signOut.php">Sign out</a>';
    if ((int)$_SESSION['user']['role'] === 1) {
        $sign .= "\n<a href='/admin.php'>Admin</a>\n
                    <a href='/productCRUD/createProduct.php' target='_blank'>Create</a>\n
                    <a href='/productCRUD/showProduct.php' target='_blank'>Read</a>\n
                    <a href='/productCRUD/updateProduct.php' target='_blank'>Update</a>\n
                    <a href='/productCRUD/deleteProduct.php' target='_blank'>Delete</a>";
    }
    $orderBtn = "<a href='/myOrders.php'>View orders</a>\n<button class='order-btn'>Order</button>";
}

echo render(TEMPLATES_DIR . 'product.tpl', [
    'title' => 'Products',
    'class' => 'products',
    'sign' => $sign,
    'orderbtns' => $orderBtn,
    'content' => generateProduct($sqlProducts),
    'cartcontent' => generateCart($sqlCart) ? generateCart($sqlCart) : '<i>Cart is empty</i>',
    'subtotal' => getCartSubtotal($userId),
    'style' => generateCss($sqlCss),
]);