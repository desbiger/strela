<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => "������ ����������� ��������",
	"DESCRIPTION" => "������� ������ �����������",
	"ICON" => "/images/cat_list.gif",
	"CACHE_PATH" => "Y",
	"SORT" => 30,
	"PATH" => array(
		"ID" => "��������� ����������",
		"CHILD" => array(
			"ID" => "velo",
			"NAME" => "����������",
			"SORT" => 30,
			"CHILD" => array(
				"ID" => "catalog_list_random",
			),
		),
	),
);

?>