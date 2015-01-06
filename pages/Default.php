<?php
//Формирование основного контента главрной страницы
$categories = Utils::getAllCategories();
if(empty($categories)){
    $p = 'Пока нет ни одной категории';
}else{
    $p = Utils::getHtmlListOfCategories($categories);
}