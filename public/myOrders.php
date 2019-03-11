<?php

require_once __DIR__ . '/../config/config.php';

$userId = (int)$_SESSION['user']['id'] ?? 0;
$role = (int)$_SESSION['user']['role'] ?? 0;
$sqlCss = "SELECT * FROM `css` WHERE `name` LIKE 'product'";
$buttons = "<a href='/signOut.php'>Sign out</a>";

if ($role === 1) {
    $buttons .= "\n<a href='/admin.php'>Admin</a>";
} else {
    $buttons .= "\n<a href='/product.php'>Continue</a>";
}

echo render(TEMPLATES_DIR . 'myOrders.tpl', [
    'title' => 'Orders',
    'class' => 'user-orders',
    'buttons' => $buttons,
    'content' => generateOrderList($userId) ? generateOrderList($userId) : '<h2>You have no orders yet!</h2>',
    'style' => generateCss($sqlCss),
]);