<?
	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
		die();
	}

	if (!CModule::IncludeModule("iblock")) {
		return;
	}

	CModule::IncludeModule('my_module');
	$props        = new velo_properties();
	foreach($props->result as $key => $vol){
		$props_list[] = $key;
	}
	$arIBlockType = CIBlockParameters::GetIBlockTypes();

	$arComponentParameters = array(

		"PARAMETERS" => array(
			'PROPERTIES_LIST' => array(
				'NAME' => "Свойства которые надо выводить",
				'TYPE' => "LIST",
				'VALUES' => $props_list,
				'MULTIPLE' => 'Y'
			),
		),
	);

?>