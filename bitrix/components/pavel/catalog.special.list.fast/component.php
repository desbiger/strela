<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

	CModule::IncludeModule('my_module');
	CModule::IncludeModule('iblock');

	$iblock_id = velo::$Iblock_id;
	$kompred_id = velo::$property_id;
	$color_id = velo::$color_property_id;

	$codes = my::GetPropdertyCodesByIblockID(43);
?>
	<!--<pre>--><?//print_r($codes)?><!--</pre>-->
<?




	$q = "
SELECT
    `element`.*
    , `price`.`PRICE`,
    iblock.`NAME` as IBLOCK_NAME,`iblock`.`DETAIL_PAGE_URL`
FROM
    `b_iblock_element` AS `element`
    INNER JOIN `b_iblock_element_property` AS `kompred`
        ON (`element`.`ID` = `kompred`.`VALUE`)
    INNER JOIN `b_catalog_price` AS `price`
        ON (`price`.`PRODUCT_ID` = `kompred`.`IBLOCK_ELEMENT_ID`)
        INNER JOIN `b_iblock` AS iblock
        ON(`element`.`IBLOCK_ID` = `iblock`.`ID`)

WHERE
`kompred`.`IBLOCK_ELEMENT_ID` IN(SELECT
            el.ID
            FROM
             b_iblock_element AS el,
             `b_catalog_product` AS q

             WHERE
              `el`.`ID` = q.`ID` AND
               q.`QUANTITY` > 0
                )
AND (`kompred`.`IBLOCK_PROPERTY_ID` = {$kompred_id})

GROUP BY `element`.`ID`
##LIMIT 300
;
";
	//	echo "<pre>" . $q . "</pre>";
	$res = $DB->Query($q);
	while ($t = $res->Fetch()) {
		$t['PRICE'] = preg_replace("/([0-9]{1,2})([0-9]{3})\.([0-9]{2})/","$1 $2 руб",$t['PRICE']);
		$t['DETAIL_PAGE_URL'] = preg_replace("/\#SITE_DIR\#/", "", $t['DETAIL_PAGE_URL']);
		$t['DETAIL_PAGE_URL'] = preg_replace("/\#ELEMENT_ID\#/", $t['ID'], $t['DETAIL_PAGE_URL']);

		$arResult[] = $t;
	}
	shuffle($arResult);
	$i = 0;
	$res = array();
	foreach ($arResult as $vol) {
		$i++;
		if ($i < 30) {
			$res[] = $vol;
		}
	}
	$arResult = $res;

	$this->IncludeComponentTemplate();
?>