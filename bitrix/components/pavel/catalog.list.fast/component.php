<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

	CModule::IncludeModule('my_module');
	CModule::IncludeModule('iblock');



	$iblock_id = velo::$Iblock_id;
	$kompred_id = velo::$property_id;
	$kompred_iblock_id = velo::$property_iblock_id;
	$color_id = velo::$color_property_id;

	$codes = my::GetPropdertyCodesByIblockID($iblock_id);
	$or_logik_codes = velo_properties::GetVeloTypes();
?>

<!--<pre>-->
<!--    --><?//print_r($_REQUEST)?>
<!--    --><?//print_r($or_logik_codes)?>
<!--</pre>-->

<?
	//	magic_quotes_runtime(1);
	if ($_REQUEST['SEIZE']) {
		$_REQUEST['RAZMER_KOLESA'] = explode("_", preg_replace("/([0-9]+)/", "$1\"", $_REQUEST['SEIZE']));
	}
?>

<?
	//----------------------------------------Генерируем запрос--------------------------------------------
	$q_dop = "";
	foreach ($_REQUEST as $request_code => $req_value) {

		if ($req_value != '' && in_array($request_code, $codes) && !in_array($request_code, $or_logik_codes)) {

//            echo '111';

            if (count($req_value) > 1) {
				foreach ($req_value as &$ttt){
					$ttt = "'" . $ttt . "'";
				}
				$value = "IN(" . implode(",", $req_value) . ")";
			}
			else {
				$value = "= '";
				$value .= is_array($req_value) ? $req_value[0] : $req_value;
				$value .= "'";
			}

			$prop_id = velo_properties::GetPropIDByCode($request_code);

			$q_dop .= "
			INNER JOIN b_iblock_element_property as {$request_code}
			ON ({$request_code}.VALUE {$value} AND
			{$request_code}.IBLOCK_PROPERTY_ID = '{$prop_id['ID']}' AND
			{$request_code}.IBLOCK_ELEMENT_ID = element.ID)
			";
		}
		elseif (in_array($request_code, $or_logik_codes)) {

//            echo '222';

			if ($dop_where == '') {
				$dop_where = "WHERE (";
			}
			else {
				$dop_where .= " OR ";
			}
			if (count($req_value) > 1) {
				foreach ($req_value as &$ttt) {
					$ttt = "'" . $ttt . "'";
				}
				$value = "IN(" . implode(",", $req_value) . ")";
			}
			else {
				$value = "= '";
				$value .= is_array($req_value) ? $req_value[0] : $req_value;
				$value .= "'";

			}
			$prop_id = velo_properties::GetPropIDByCode($request_code);

			$q_dop .= "
			LEFT JOIN b_iblock_element_property as {$request_code}
			ON (
			{$request_code}.IBLOCK_PROPERTY_ID = '{$prop_id['ID']}' AND
			{$request_code}.IBLOCK_ELEMENT_ID = element.ID)
			";
			$dop_where .= "{$request_code}.VALUE = 'Да'";
		}
	}
	if (strlen($dop_where) > 1) {
		$dop_where .= ")";
	}

	if (isset($_REQUEST['price_min']) && isset($_REQUEST['price_max']) && $_REQUEST['price_max'] != '' && $_REQUEST['price_min'] != '') {
		$q_dop .= "
		 INNER JOIN b_catalog_price as filter_price
		 ON (filter_price.PRICE > '" . $_REQUEST['price_min'] . "'
		 AND filter_price.PRICE < '" . $_REQUEST['price_max'] . "' AND
		 filter_price.PRODUCT_ID = element.ID)
		";
	}

	if ($_REQUEST['TYPE']) {
		$request_code = $_REQUEST['TYPE'];
		$prop_id = velo_properties::GetPropIDByCode($request_code);
		$q_dop .= "
					INNER JOIN b_iblock_element_property as {$request_code}
					ON ({$request_code}.VALUE = 'да' AND
					{$request_code}.IBLOCK_PROPERTY_ID = '{$prop_id['ID']}' AND
					{$request_code}.IBLOCK_ELEMENT_ID = element.ID)
					";

	}

	if ($_REQUEST['BRAND']) {
		$value        = $_REQUEST['BRAND'];
		$request_code = 'MARKA';
		$prop_id = velo_properties::GetPropIDByCode($request_code);
		$q_dop .= "
					INNER JOIN b_iblock_element_property as {$request_code}
					ON ({$request_code}.VALUE = '{$value}' AND
					{$request_code}.IBLOCK_PROPERTY_ID = '{$prop_id['ID']}' AND
					{$request_code}.IBLOCK_ELEMENT_ID = element.ID)
					";

	}
	//----------------------------------------Генерируем запрос--------------------------------------------

	//--------------------------генерируем постраничную навигацию-------------------------------------
	$q_count = "
SELECT
    COUNT(`element`.`ID`)
FROM
    `b_iblock_element` AS `element`
    INNER JOIN `b_iblock_element_property` AS `kompred`
        ON (`element`.`ID` = `kompred`.`VALUE` AND `kompred`.`IBLOCK_PROPERTY_ID` = {$kompred_id}
        AND `kompred`.`IBLOCK_ELEMENT_ID` IN(SELECT
                    el.ID
                    FROM
                     b_iblock_element AS el,
                    `b_catalog_product` AS q

                                 WHERE
                                  `el`.`ID` = q.`ID` AND
                                   q.`QUANTITY` > 0
                        )
                                )
    INNER JOIN `b_catalog_price` AS `price`
        ON (`price`.`PRODUCT_ID` = `kompred`.`IBLOCK_ELEMENT_ID`)
        INNER JOIN `b_iblock` AS iblock
        ON(`element`.`IBLOCK_ID` = `iblock`.`ID`)
         {$q_dop}
 {$dop_where}
GROUP BY `element`.`ID`
;
";
	$elements = $DB->Query($q_count);
	$i = 0;
	while ($elements->Fetch()) {
		$i++; //количество элементов
	}
	$pages = floor($i / 30);

	$html = $pages > 0 ? "<ul class='navigation'>
	<li>
	  <span><a href='?PAGEN=1'>Вначало</a></span>
	</li>
	" : '';
	$count = 0;
	while ($count < $pages) {
		if ($_SERVER['QUERY_STRING'] && !preg_match("/^PAGEN=[0-9]*$/", $_SERVER['QUERY_STRING'])) {
			$link = "?" . preg_replace("/(\&PAGEN=[0-9]*)/", "", $_SERVER['QUERY_STRING']) . "&";
		}
		else {
			$link = "?";
		}
		$class = $_REQUEST['PAGEN'] == $count + 1 ? "class = 'selected'" : "";
		$count++;
		if ($count <= 15) {
			$add_to_end = true;
			$html .= "<li ><a {$class} href=\"{$link}PAGEN={$count}\">{$count}</a></li>";
		}
	}
	$html .= $add_to_end ? "
	<li>...</li>
	<li>
	<a href='{$link}PAGEN={$pages}'>{$pages}</a>
	</li>
		<li>
	  <span><a href='{$link}PAGEN={$pages}'>В конец</a></span>
	</li>
	</ul>" : "</ul>";
	$pagen = $html;
	//--------------------------генерируем постраничную навигацию-------------------------------------

?>
	<!--		<pre>--><?//print_r($_REQUEST)?><!--</pre>-->
<?
	$limit = isset($_REQUEST['PAGEN']) ? "LIMIT " . ($_REQUEST['PAGEN'] * 30) . ",30" : "LIMIT 30";
	$q = "
SELECT
    `element`.*
    , `price`.`PRICE`,
    iblock.`NAME` as IBLOCK_NAME,`iblock`.`DETAIL_PAGE_URL`
FROM
    `b_iblock_element` AS `element`

    INNER JOIN `b_iblock_element_property` AS `kompred`
            ON (`element`.`ID` = `kompred`.`VALUE` AND `kompred`.`IBLOCK_PROPERTY_ID` = {$kompred_id}
            AND `kompred`.`IBLOCK_ELEMENT_ID` IN(SELECT
            el.ID
            FROM
             b_iblock_element AS el,
             `b_catalog_product` AS q

             WHERE
              `el`.`ID` = q.`ID` AND
               q.`QUANTITY` > 0
                )
                        )
    INNER JOIN `b_catalog_price` AS `price`
        ON (`price`.`PRODUCT_ID` = `kompred`.`IBLOCK_ELEMENT_ID`)
        INNER JOIN `b_iblock` AS iblock
        ON(`element`.`IBLOCK_ID` = `iblock`.`ID`)
        {$q_dop}
 {$dop_where}

GROUP BY `element`.`ID`
ORDER BY `price`.`PRICE`
{$limit}
";


?>
<!--				<pre>--><?//=$q?><!--</pre>-->
<?
	$res = $DB->Query($q);
	while ($t = $res->Fetch()) {
		$props = CIBlockElement::GetProperty($t['IBLOCK_ID'], $t['ID']);
		while ($properties = $props->GetNext()) {
			$t['PROPERTIES'][$properties['CODE']] = $properties;
		}
		$t['DETAIL_PAGE_URL'] = preg_replace("/\#SITE_DIR\#/", "", $t['DETAIL_PAGE_URL']);
		$t['DETAIL_PAGE_URL'] = preg_replace("/\#ELEMENT_ID\#/", $t['ID'], $t['DETAIL_PAGE_URL']);

		$arResult['ITEMS'][] = $t;
	}
	$arResult['NAV_STRING'] = $pagen;
?>

<!--<pre>-->
<!--    --><?//print_r($arResult)?>
<!--</pre>-->


<?
	$this->IncludeComponentTemplate();
?>