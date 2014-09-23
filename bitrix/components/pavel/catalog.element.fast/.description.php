<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => "Детальная страницы велосипеда",
	"DESCRIPTION" => "Выводит все данные по велосипеду",
	"ICON" => "/images/cat_list.gif",
	"CACHE_PATH" => "Y",
	"SORT" => 30,
	"PATH" => array(
		"ID" => "Кастомные компоненты",
		"CHILD" => array(
			"ID" => "velo",
			"NAME" => "Велосипеды",
			"SORT" => 30,
			"CHILD" => array(
				"ID" => "catalog_element",
			),
		),
	),
);

?>