<?php
function linkCss($cssPath){
    echo '<link rel="stylesheet" href="' . BASE_URL . '/' . $cssPath . '">';
}

function linkJs($jsPath){
    echo '<script src="' . BASE_URL . '/' . $jsPath . '"></script>';
}
