<?php

require_once __DIR__ . '/../config/config.php';

$imgId = (isset($_GET['id'])) ? $_GET['id'] : false;
$sqlImg = 'SELECT * FROM `images` ORDER BY `images`.`views` DESC';
$sqlCss = 'SELECT * FROM `css`';
$sqlJs = 'SELECT * FROM `js`';
$sqlSingleImg = "SELECT * FROM `images` WHERE `id`='$imgId'";

if ($imgId) {
    $img = show($sqlSingleImg);

    echo render(TEMPLATES_DIR . 'single-img.tpl', [
        'title' => 'Car',
        'class' => 'galleryWrapper',
        'name' => $img['title'],
        'views' => ($img['views'] > 0) ? $img['views'] : 'no views',
        'content' => generateGallery(getAssocResult($sqlSingleImg)),
        'style' => generateCss($sqlCss),
    ]);
    updateViews($imgId, $img['views']);
} else {
    echo render(TEMPLATES_DIR . 'index.tpl', [
        'title' => 'Cars',
        'class' => 'gallery',
        'content' => generateGallery(getAssocResult($sqlImg)),
        'style' => generateCss($sqlCss),
        'js' => generateJs($sqlJs),
    ]);
}