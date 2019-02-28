<?php

function createConnection()
{
    $db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    mysqli_query($db, "SET CHARACTER SET 'utf8'");
    return $db;
}
function execQuery($sql)
{
    $db = createConnection();
    $result = mysqli_query($db, $sql);
    mysqli_close($db);
    return $result;
}
function getAssocResult($sql)
{
    $db = createConnection();
    $result = mysqli_query($db, $sql);
    $array_result = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $array_result[] = $row;
    }
    mysqli_close($db);
    return $array_result;
}
function show($sql)
{
    $result = getAssocResult($sql);
    if(empty($result)) {
        return null;
    }
    return $result;
}
function updateViews($id, $views) {
    $newViews = ($views > 0) ? $views + 1 : 1;
    $sql = "UPDATE `images` SET `views`='$newViews' WHERE `images`.`id`='$id'";
    $db = createConnection();
    $result = mysqli_query($db, $sql);
    mysqli_close($db);
    if (!$result) {
        echo "Update unsuccessful";
    }
}