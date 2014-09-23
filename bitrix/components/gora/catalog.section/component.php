<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}
	include_once('classes/velo_catalog.php');

	CModule::IncludeModule('iblock');
	CModule::IncludeModule('my_module');


	$base = array(
			'accessories' => 'Аксессуары',
			'velo' => 'Велосипеды',
			'velo-parts' => 'Велозапчасти',
			'veloekipirovka' => 'Велоэкипировка',
			'veloinstrumenty' => 'Велоинструменты',
			'automobile' => 'Автомасла',
			'batteries' => 'Аккумуляторы',
	);
	$iblocks = array(
			'velo-parts' => 64,
	);


	foreach ($base as $key => $vol) {
		if (key_exists($key, $iblocks)) {
			$arResult['ITEMS'][$vol]['types'] = Velo::GetRootSectionsByIblockID($iblocks[$key],'/velo-parts/');
		}
		else {
			$arResult['ITEMS'][$vol] = $key;
		}
	}



	$arResult['ITEMS']['Велосипеды'] = array('brands' => velo_catalog::getBrends());

?>
<!--	<pre>--><?//print_r($arResult)?><!--</pre>-->
<?

	$arResult['ITEMS']['Велосипеды']['types'] = array(
			'Горные',
			'Горные женские',
			'Горные 29\'',
			'Горные двухподвесные',
			'Гибридные',
			'Дорожные',
			'Складные',
			'Подростковые',
			'Детские',
			'Самокаты',
			'Шоссейные',
			'Циклокроссовые',
			'Электро',
	);

	$value = null;
	$types = array();

	foreach ($arResult['ITEMS']['Велосипеды']['types'] as $value) {

		if ($value == 'Детские') {
			$types[] = array(
					'URL' => '/type/DETSKIE/',
					'VALUE' => $value,
					'SUBMENU' => velo_catalog::getBrandsByEge('DETSKIE')
			);
		}
		else {
			$types[] = array(
					'VALUE' => $value,
					'URL' => '/type/' . velo_catalog::$types[$value] . "/",
					'SUBMENU' => velo_catalog::getBrandsByType($value)
			);
		}

	}
	$arResult['ITEMS']['Велосипеды']['types'] = $types;


	$this->IncludeComponentTemplate();
?>