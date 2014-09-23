<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

	CModule::IncludeModule('my_module');
	CModule::IncludeModule('iblock');
	CModule::IncludeModule('catalog');
	if($_REQUEST['ID'] && isset($_REQUEST['action'])){
		$tovar = $_REQUEST['ID'];
		$price = velo::GetVeloPrice($tovar);
		$name = CIblockElement::GetByID($tovar)->Fetch();
		$fields = array(
			'PRODUCT_ID' => $tovar,
			'CURRENCY' => 'RUB',
			'PRICE' => $price[0]['PRICE'],
			'LID' => 's1',
			'NAME' => $name['NAME'],
		);
		if(CSaleBasket::Add($fields)){
			LocalREdirect($_REQUEST['BACK_URL']);
		}


	}

	if($_REQUEST['ELEMENT_ID']){
		$res = CIBlockElement::GetByID($_REQUEST['ELEMENT_ID'])->GetNextElement();
		$props = $res->GetProperties();
		$fields = $res->GetFields();
		$fields['PROPERIES'] = $props;
		$arResult = $fields;
		$arResult['OFFERS'] = velo::GetOffers($arResult['ID'],Velo::GetCompredPropertyID($fields['IBLOCK_ID']));

//		$arResult['COLORS'] = velo::GetColorsByTovarID($arResult['ID']);
		$arResult['SIZE'] = velo::GetSizeByTovarID($arResult['ID']);
		$arResult['COLORS'] = velo::GetColorBySize($arResult['ID'],$arResult['SIZE'][0]['VALUE']);
		if(count($arResult['COLORS']) == 0){
			$arResult['COLORS'] = velo::GetColorsByTovarID($arResult['ID']);
		}
	}else{
		$arResult['ERRORS'] = 'Нет ни одного элемента';
	}

?>

<?

	$this->IncludeComponentTemplate();
?>