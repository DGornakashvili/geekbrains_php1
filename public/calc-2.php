<?php

function mathOperation()
{
    if (isset($_POST['number-1'], $_POST['number-2'])) {
        $arg1 = (int)$_POST['number-1'];
        $arg2 = (int)$_POST['number-2'];
        $operation = $_POST['add'] ?? $_POST['subtract'] ?? $_POST['multiply'] ?? $_POST['divide'];
        $result = 0;

        switch ($operation) {
            case '+':
            case 'add':
                $result = $arg1 + $arg2;
                break;
            case '-':
            case 'subtract':
                $result = $arg1 - $arg2;
                break;
            case '*':
            case 'multiply':
                $result = $arg1 * $arg2;
                break;
            case '/':
            case 'divide':
                $result = $arg2 !== 0 ? round(($arg1 / $arg2), 2) : 'Делить на ноль нельзя';
                break;
        }
        return "$arg1 $operation $arg2 = $result";
    }
    return 'Введите значения и выберите тип операции';
}
?>

<h1><?= mathOperation(); ?></h1>

<form action="" method="POST">
    <label>Введите первое число: <input type="number" name="number-1" required></label><br>
    <label>Введите второе число: <input type="number" name="number-2" required></label><br>
    <input type="submit" name="add" value="+">
    <input type="submit" name="subtract" value="-">
    <input type="submit" name="multiply" value="*">
    <input type="submit" name="divide" value="/">
</form>
