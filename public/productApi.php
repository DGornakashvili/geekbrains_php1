<?php

require_once __DIR__ . '/../config/config.php';

$id = $_POST['id'] ?? '';
$method = $_POST['method'] ?? '';
$uName = $_POST['uName'] ?? '';
$uLogin = $_POST['uLogin'] ?? '';
$uPass = $_POST['uPass'] ?? '';

if ($id) {
    if ($method === 'add') {
        $sqlCart = "SELECT * FROM `cart` WHERE `id`=$id";
        $product = show($sqlCart);

        if (!empty($product)) {
            $quantity = $product['quantity'] + 1;
            $sqlUpdate = "UPDATE `cart` SET `quantity`=$quantity WHERE `id`=$id";
            execQuery($sqlUpdate);
            ajaxResult('update', $quantity);
        } else {
            $sqlGet = "SELECT * FROM `products` WHERE `id`=$id";
            $product = show($sqlGet);
            $id = $product['id'];
            $name = $product['name'];
            $price = $product['price'];
            $image = $product['image'];
            $sqlInsert = "INSERT INTO `cart` (`id`, `name`, `price`, `image`) VALUES ($id,'$name', '$price', '$image')";
            $result = execQuery($sqlInsert);

            if ($result) {
                $data = generateCart($sqlCart);
                ajaxResult('create', $data);
            }
        }
    } elseif ($method === 'remove') {
        $sqlDelete = "DELETE FROM `cart` WHERE `id`=$id";
        execQuery($sqlDelete);
    }
    return;
}

if ($uLogin && $uPass) {
    $db = createConnection();
    $uName = escapeString($db, $uName);
    $uLogin = escapeString($db, $uLogin);
    $uPass = escapeString($db, $uPass);
    $uPass = md5($uPass);

    if ($method === 'signIn') {
        $sqlFindUser = "SELECT * FROM `users` WHERE `login` LIKE '$uLogin' AND `password` LIKE '$uPass'";
        $user = show($sqlFindUser);

        if (!empty($user)) {
            $_SESSION['user'] = $user;
            $data = generateSISuccess($user, "You are welcome!");
            ajaxResult('signIn', $data);
        } else {
            $data = generateSIError('Incorrect Login or Password');
            ajaxResult('signIn', $data, true);
        }
    } elseif ($method === 'signUp') {
        if (!$uName) {
            $data = generateSIError("Please, enter your name");
            ajaxResult('signUp', $data, true);
            return;
        }

        $sqlFindUser = "SELECT * FROM `users` WHERE `login` LIKE '$uLogin'";
        $user = show($sqlFindUser);

        if (!empty($user)) {
            $data = generateSIError("Login $uLogin is already occupied");
            ajaxResult('signUp', $data, true);
            return;
        }

        $sqlAddUser = "INSERT INTO `users` (`name`, `login`, `password`) VALUES ('$uName', '$uLogin', '$uPass')";
        $result = execQuery($sqlAddUser);

        if ($result) {
            $sqlFindUser = "SELECT * FROM `users` WHERE `login` LIKE '$uLogin' AND `password` LIKE '$uPass'";
            $user = show($sqlFindUser);
            $_SESSION['user'] = $user;
            $data = generateSISuccess($user, "Your account created!");
            ajaxResult('signUp', $data);
        } else {
            $data = generateSIError('Incorrect values, try again');
            ajaxResult('signUp', $data, true);
        }
    }
} else {
    $data = generateSIError('No Login or Password');
    ajaxResult('signIn', $data, true);
}

