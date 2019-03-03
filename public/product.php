<?php

require_once __DIR__ . '/../config/config.php';

$sqlProducts = 'SELECT * FROM `products`';
$sqlCss = "SELECT * FROM `css` WHERE `name` LIKE 'product'";

echo render(TEMPLATES_DIR . 'product.tpl', [
    'title' => 'Products',
    'class' => 'products',
    'content' => generateProduct($sqlProducts),
    'style' => generateCss($sqlCss),
]);