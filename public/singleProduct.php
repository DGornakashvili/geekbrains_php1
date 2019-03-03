<?php

require_once __DIR__ . '/../config/config.php';

$id = (int)$_GET['id'] ?? false;
$sqlProduct = "SELECT * FROM `products` WHERE `id` = $id";
$sqlCss = "SELECT * FROM `css` WHERE `name` LIKE 'product'";

$product = show($sqlProduct);

echo render(TEMPLATES_DIR . 'singleProduct.tpl', [
    'title' => 'Product',
    'class' => 'single-product',
    'style' => generateCss($sqlCss),
    'name' => $product['name'],
    'price' => $product['price'],
    'text' => $product['description'],
    'src' => PRODUCT_IMG_DIR . $product['image'],
]);