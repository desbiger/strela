<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

	CModule::IncludeModule('iblock');
	$arFilter = Array(
		"IBLOCK_ID" => 30, //—юда нужно загнать нужный id инфоблока
		"PROPERTY_MARKA" => $_REQUEST['MARKA'],
		"PROPERTY_TIP_VELOSIPEDA" => urldecode($_REQUEST['TYPE']),
	);

	$data = CIBlockElement::GetList(null, $arFilter);
	while ($elements = $data->GetNextElement()) {
		$f = $elements->GetFields();
		$f['PROP'] = $elements->GetProperties();
		$arResult[] = $f;
	}


	$this->IncludeComponentTemplate($componentPage);
?>