<?php

require_once __DIR__ . '/../../config/config.php';

$id = $_POST['id'] ?? false;

if ($id) {
    $sqlProduct = "SELECT * FROM `products` WHERE `id` = $id";
    $sqlCss = "SELECT * FROM `css` WHERE `name` LIKE 'product'";

    $product = show($sqlProduct);

    if(!$product) {
        echo "<h1>No product with ID $id was found</h1>";
        return;
    }

    echo render(TEMPLATES_DIR . 'singleProduct.tpl', [
        'title' => 'Product',
        'class' => 'single-product',
        'style' => generateCss($sqlCss),
        'name' => $product['name'],
        'price' => $product['price'],
        'text' => $product['description'],
        'src' => PRODUCT_IMG_DIR . $product['image'],
    ]);
} else {
    echo render(TEMPLATES_DIR . 'requestIdForm.tpl', [
        'title' => 'Show',
    ]);
}