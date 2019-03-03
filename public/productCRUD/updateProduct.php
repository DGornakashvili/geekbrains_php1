<?php

require_once __DIR__ . '/../../config/config.php';

$id = $_POST['id'] ?? false;

if (isset($_POST['name']) || isset($_POST['description']) || isset($_POST['price'])) {
    $sqlGet = "SELECT * FROM `products` WHERE `id`=$id";
    $product = show($sqlGet);
    $db = createConnection();
    $name = $_POST['name'] === '' ? $product['name'] : escapeString($db, $_POST['name']);
    $text = $_POST['description'] === '' ? $product['description'] : escapeString($db, $_POST['description']);
    $price = $_POST['price'] === '' ? $product['price'] : $_POST['price'];
    $image = uploadFile('image') ? basename($_FILES['image']['name']) : $product['image'];
    $sqlUpdate = "UPDATE `products` SET `name`= '$name',`description`= '$text',`price`= '$price',`image`= '$image' WHERE `products`.`id`=$id";

    $result = execQuery($sqlUpdate);

    echo $result ? "<h1>Product with ID $id was successfully updated</h1>" : "<h1>Something has gone wrong</h1>";
} elseif ($id) {
    $sqlGet = "SELECT * FROM `products` WHERE `id`=$id";
    $product = show($sqlGet);

    echo render(TEMPLATES_DIR . 'updateForm.tpl', [
        'title' => 'Update',
        'id' => $product['id'],
        'name' => $product['name'],
        'text' => $product['description'],
        'price' => $product['price'],
        'image' => $product['image'],
    ]);
    $_POST['id'] = $id;
} else {
    echo render(TEMPLATES_DIR . 'requestIdForm.tpl', [
        'title' => 'Update',
    ]);
}