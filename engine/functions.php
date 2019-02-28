<?php

function renderItems($array) {
    $result = '';

    foreach ($array as $item) {
        $str = strlen($item);
        if ($str < 5) {
            continue;
        }

        if (strpos($item, '.jpg')) {
            $src = 'img/' . $item;
            $alt = str_replace(['_', '.jpg'], [' ', ''], $item);
            $result .= "<img src='$src' alt='$alt'>";
        } elseif (strpos($item, '.css')) {
            $href = 'css/'. $item;
            $result .= "<link rel='stylesheet' href='$href'>";
        } elseif (strpos($item, '.js')) {
            $src = 'js/' . $item;
            $result .= "<script src='$src'></script>";
        }
    }
    return $result;
}

function render($file, $variables = []) {
	if (!is_file($file)) {
		echo 'Template file "' . $file . '" not found';
		exit();
	}

	if (filesize($file) === 0) {
		echo 'Template file "' . $file . '" is empty';
		exit();
	}

	$templateContent = file_get_contents($file);

	if (empty($variables)) {
		return $templateContent;
	}

	foreach ($variables as $key => $value) {
	    if (is_array($value)) {
	        $value = renderItems($value);
        }

		if (empty($value) || !is_string($value)) {
			continue;
		}

		$key = '{{' . strtoupper($key) . '}}';

		$templateContent = str_replace($key, $value, $templateContent);
	}

	return $templateContent;
}

function generateGallery($array)
{
    $result= '';

    foreach ($array as $image) {
        if (is_file($image['url'])) {
            $result .= render(TEMPLATES_DIR . 'galleryItem.tpl', [
                'id' => $image['id'],
                'src' => $image['url'],
                'alt' => $image['title'],
                'views' => ($image['views'] > 0) ? $image['views'] : 'no views',
            ]);
        }
    }
    return $result;
}
function generateCss($sql)
{
    $result= '';
    $array = getAssocResult($sql);
    foreach ($array as $cssItem) {
        if (is_file($cssItem['url'])) {
            $result .= render(TEMPLATES_DIR . 'cssItem.tpl', [
                'href' => $cssItem['url'],
            ]);
        }
    }
    return $result;
}
function generateJs($sql)
{
    $result= '';
    $array = getAssocResult($sql);
    foreach ($array as $jsItem) {
        if (is_file($jsItem['url'])) {
            $result .= render(TEMPLATES_DIR . 'jsItem.tpl', [
                'src' => $jsItem['url'],
            ]);
        }
    }
    return $result;
}
