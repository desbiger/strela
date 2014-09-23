<?
	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
		die();
	}

	if (!CModule::IncludeModule("iblock")) {
		return;
	}

	$arIBlockType = CIBlockParameters::GetIBlockTypes();


	$arComponentParameters = array(

			"PARAMETERS" => array(
					'iblock_id' => array(
							'TYPE' => 'STRING',
							'NAME' => 'IBLOCK_ID'
					)
			),
	);

?>