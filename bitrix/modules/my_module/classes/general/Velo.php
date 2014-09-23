<?

	class Velo
	{

//		static $Iblock_id = 55;
//		static $property_iblock_id = 56; //айди инфоблока торговых предложений
//		static $property_id = 783; //айди свойства связи торговых предложений с элементом
//		static $color_property_id = 786; //айди свойства цветов и размеров

        static $Iblock_id = 99;
        static $property_iblock_id = 100; //айди инфоблока торговых предложений

        static $property_id = 1552; //айди свойства связи торговых предложений с элементом
        static $color_property_id = 1254; //айди свойства цветов и размеров


		static function AddWatermark($file)
		{
			$rif = CFile::ResizeImageFile($sourceFile = $file, $destinationFile = $_SERVER['DOCUMENT_ROOT'] . "/1.jpg", $arSize = array(
					'width' => 1200,
					'height' => 1200
			), $resizeType = BX_RESIZE_IMAGE_PROPORTIONAL, $arWaterMark = array(), $jpgQuality = false, $arFilters = Array( // нанесение водяного знака
					array(
							"name" => "watermark",
							"position" => "center",
							"file" => $_SERVER['DOCUMENT_ROOT'] . "/include/image/zaglushka.png"
					)
			));
			if ($rif) {
				unlink($file);
				copy($_SERVER['DOCUMENT_ROOT'] . "/1.jpg", $file);
			}
		}

		static function GetRootSectionsByIblockID($iblock_id,$link_before = null)
		{
			$result = array();
			global $DB;
			$q = "
			SELECT
			  *
			FROM
			  `b_iblock_section` AS s
			WHERE s.`IBLOCK_ID` = {$iblock_id}
			  AND s.`DEPTH_LEVEL` = 1
			";
			$r = $DB->Query($q);
			while ($t = $r->Fetch()) {
				$result[$t['NAME']] = array('URL' => $link_before . $t['ID']."/",'VALUE' => $t['NAME']);
			}
			return $result;
		}

		static function GetCompredIblockID($iblock_id)
		{
			global $DB;
			$q = "SELECT
			  prop.`IBLOCK_ID`
			FROM
			  b_iblock_property AS prop
			WHERE
			   prop.`LINK_IBLOCK_ID` = {$iblock_id} ";

			if ($res = $DB->Query($q)
					->Fetch()
			) {
				return $res['IBLOCK_ID'];
			}
			else {
				return false;
			}
		}

		static function GetCompredPropertyID($iblock_id)
		{
			global $DB;
			$q = "SELECT
			  prop.`ID`
			FROM
			  b_iblock_property AS prop
			WHERE
			   prop.`LINK_IBLOCK_ID` = {$iblock_id} ";

			if ($res = $DB->Query($q)
					->Fetch()
			) {
				return $res['ID'];
			}
			else {
				return false;
			}
		}

		/**
		 * @param $parent_id айди велосипеда не из ком предложений
		 * @return bool
		 * Проверка на наличие велосипедов
		 */
		static function CheckCountByParent($parent_id)
		{
			global $DB;
			$prop_id = self::$property_id;
			$Q       = "SELECT
			  *
			FROM
			  `b_iblock_element_property` AS prop
			WHERE prop.`IBLOCK_PROPERTY_ID` = {$prop_id}
			  AND prop.`VALUE` = {$parent_id}
			  AND prop.`IBLOCK_ELEMENT_ID` IN
			  (SELECT
			    `product_count`.`ID`
			  FROM
			    `b_catalog_product` AS product_count
			  WHERE `product_count`.`QUANTITY` > 0)";
			return $res = $DB->Query($Q)
					->SelectedRowsCount();
		}

		static function GetPriceVeloByComPredlozh()
		{
			$tor_connect = self::$property_id;
			$iblock_id   = self::$Iblock_id;
			global $DB;
			$q = "
			SELECT
			   velo.ID,
			   velo.IBLOCK_ID,
			   velo.NAME,
			   predlozh.ID as predlozhenie,
			   predlozh.NAME as predName,
			   price.PRICE as price
			FROM
			 b_iblock_element as velo
			 LEFT JOIN b_iblock_element_property AS property
			 ON(property.VALUE = velo.ID)

			 LEFT JOIN b_iblock_element AS predlozh
			 ON(property.IBLOCK_ELEMENT_ID = predlozh.ID)

			 LEFT JOIN b_catalog_price AS price
			 ON(price.PRODUCT_ID = predlozh.ID)

			 WHERE
			   velo.IBLOCK_ID = {$iblock_id} AND
			   property.IBLOCK_PROPERTY_ID = {$tor_connect}
			   GROUP BY velo.ID
			";

			$result = array();
			$temp   = $DB->Query($q);
			while ($t = $temp->Fetch()) {
				$result[] = $t;
			}
			return $result;
		}

		/**
		 * @param $element_id
		 * @return array
		 * Получение уникальных значений цвета и размера велосипеда из торговых предложений
		 */
		static function GetOffersColorsAndSize($element_id)
		{
			global $DB;
			$result            = array();
			$property_id       = self::$property_id;
			$color_property_id = self::$color_property_id;
			$q                 = "
				SELECT
				     property.*
				FROM
				    b_iblock_element_property AS svyaz
				    INNER JOIN b_iblock_element AS offers
				        ON (svyaz.IBLOCK_ELEMENT_ID = offers.ID)
				    INNER JOIN b_iblock_element_property AS property
				        ON (offers.ID = property.IBLOCK_ELEMENT_ID)
				WHERE (svyaz.IBLOCK_PROPERTY_ID = {$property_id}
				    AND svyaz.VALUE = {$element_id}
				    AND property.IBLOCK_PROPERTY_ID = {$color_property_id})
				GROUP BY property.VALUE
				ORDER BY property.DESCRIPTION ASC;
			";
			$s                 = $DB->Query($q);
			while ($t = $s->Fetch()) {
				$result[] = $t;
			}
			return $result;
		}


		/**
		 * @param $element_id
		 * @param $size
		 * @return array
		 * Получение списка цветов и айди товаров с этими цветами, по размеру велосипеда и айдишнику карточки товара
		 */
		//		static function GetColorBySize($element_id, $size)
		//		{
		//			global $DB;
		//			$result            = array();
		//			$property_id       = self::$property_id;
		//			$color_property_id = self::$color_property_id;
		//			$q                 = "
		//				SELECT
		//				  size.`VALUE`,
		//				  color.`VALUE`,
		//				  color.`IBLOCK_ELEMENT_ID` as ID
		//				FROM
		//				  `b_iblock_element_property` AS size,
		//				  `b_iblock_element_property` AS color,
		//				  `b_iblock_element_property` AS quantity
		//				WHERE size.`IBLOCK_ELEMENT_ID` = color.`IBLOCK_ELEMENT_ID`
		//				  AND color.`IBLOCK_ELEMENT_ID` = quantity.`IBLOCK_ELEMENT_ID`
		//				  AND quantity.`IBLOCK_ELEMENT_ID` IN
		//				  (SELECT
		//				    prop.`IBLOCK_ELEMENT_ID` AS ID
		//				  FROM
		//				    `b_iblock_element` AS el,
		//				    `b_iblock_element_property` AS prop
		//				  WHERE prop.`IBLOCK_PROPERTY_ID` = '{$color_property_id}'
		//				    AND el.`ID` = '{$element_id}'
		//				    AND el.`ID` = prop.`VALUE`)
		//				  AND quantity.`VALUE` > 0
		//				  AND size.`VALUE` LIKE '%{$size}%'
		//				  AND size.`DESCRIPTION` = 'Размер'
		//				  AND color.`DESCRIPTION` = 'Цвет'
		//				  AND quantity.`DESCRIPTION` = 'Количество' ";
		//
		//			$s = $DB->Query($q);
		//			while ($t = $s->Fetch()) {
		//				$result[] = $t;
		//			}
		//			return $result;
		//		}
		static function GetColorBySize($element_id, $size)
		{
			global $DB;
			$result            = array();
			$property_id       = self::$property_id;
			$color_property_id = self::$color_property_id;
			if ($size) {
				$q = "

SELECT
  *
FROM
  `b_iblock_element_property` AS color
  JOIN `b_catalog_product` AS quant
    ON quant.`ID` = color.`IBLOCK_ELEMENT_ID`
    AND `quant`.`QUANTITY` > 0
WHERE color.`DESCRIPTION` = 'Цвет'
  AND color.`IBLOCK_ELEMENT_ID` IN
  (SELECT
    size.`IBLOCK_ELEMENT_ID`
  FROM
    `b_iblock_element_property` AS size
  WHERE size.`IBLOCK_PROPERTY_ID` = '{$color_property_id}'
    AND size.`DESCRIPTION` = 'Размер'
    AND size.`VALUE` = '{$size}'
    AND size.`IBLOCK_ELEMENT_ID` IN
    (SELECT
      con.`IBLOCK_ELEMENT_ID`
    FROM
      `b_iblock_element_property` AS con
    WHERE con.`VALUE` = {$element_id}
      AND con.`IBLOCK_PROPERTY_ID` = {$property_id}))

							";
			}
			else {
				$q = "

SELECT
  *
FROM
  `b_iblock_element_property` AS color
  JOIN `b_catalog_product` AS quant
    ON quant.`ID` = color.`IBLOCK_ELEMENT_ID`
    AND `quant`.`QUANTITY` > 0
WHERE color.`DESCRIPTION` = 'Цвет'
  AND color.`IBLOCK_ELEMENT_ID` IN
  (SELECT
      con.`IBLOCK_ELEMENT_ID`
    FROM
      `b_iblock_element_property` AS con
    WHERE con.`VALUE` = {$element_id}
      AND con.`IBLOCK_PROPERTY_ID` = {$property_id})
";
			}


			$s = $DB->Query($q);
			while ($t = $s->Fetch()) {
				$result[] = $t;
			}
			return $result;
		}


		/**
		 * @param $element_id
		 * @return array
		 * Получение списка цен для велосипеда
		 */
		static function GetVeloPrice($element_id)
		{
			global $DB;
			$result      = array();
			$property_id = self::$property_id;
			$q           = "
				SELECT
				     price.*
				FROM
				    b_catalog_price AS price

				WHERE
				price.PRODUCT_ID = {$element_id}
			";
			$s           = $DB->Query($q);
			while ($t = $s->Fetch()) {
				$result[] = $t;
			}
			return $result;
		}


		/**
		 * @param $element_id
		 * @return array Получение списка комерческих предложений для товара
		 */
		static function GetOffers($element_id, $prop_id = null)
		{
			global $DB;
			$result      = array();
			$property_id = $prop_id == null ? self::$property_id : $prop_id;
			$q           = "
SELECT
  offers.*
FROM
  `b_iblock_element` AS el,
  `b_iblock_element` AS offers,
  `b_iblock_element_property` AS prop ,
   b_catalog_product AS q

WHERE prop.`IBLOCK_PROPERTY_ID` = {$property_id}
AND q.`QUANTITY` > 0
  AND q.`ID` = `offers`.`ID`
  AND offers.`ID` = prop.`IBLOCK_ELEMENT_ID`
  AND el.`ID` = {$element_id}
  AND el.`ID` = prop.`VALUE`

			";
			$s           = $DB->Query($q);
			while ($t = $s->Fetch()) {
				$price             = self::GetVeloPrice($t['ID']);
				$fields            = $t;
				$fields['OSTATOK'] = Velo::CountVeloByID($fields['ID']);
				$fields['PRICE']   = $price;
				$result[]          = $fields;
			}
			return $result;
		}


		static function CountVeloByID($id)
		{
			global $DB;
			$q = "
			SELECT
			  *
			FROM
			  `b_catalog_product` AS q
			WHERE q.`ID`  = {$id}
			";
			if ($res = $DB->Query($q)
					->Fetch()
			) {
				return $res['QUANTITY'];
			}
			else {
				return 0;
			}

		}

		/**
		 * @param $element_id
		 * @return array
		 * Получение списка цветов по айди товара
		 */
		static function GetColorsByTovarID($element_id)
		{
			global $DB;
			$result            = array();
			$property_id       = self::$property_id;
			$color_property_id = self::$color_property_id;
			$q                 = "
				SELECT
				     property.VALUE,
				     offers.ID
				FROM
				    b_iblock_element_property AS svyaz
				    INNER JOIN b_iblock_element AS offers
				        ON (svyaz.IBLOCK_ELEMENT_ID = offers.ID)
				    INNER JOIN b_iblock_element_property AS property
				        ON (offers.ID = property.IBLOCK_ELEMENT_ID),
				     `b_iblock_element_property` AS props

				     				WHERE (

				     				      props.`IBLOCK_PROPERTY_ID` = {$color_property_id}
				         				AND props.`DESCRIPTION` = 'Количество'
				         				AND props.`VALUE` > 0
				         				AND props.`IBLOCK_ELEMENT_ID` = `offers`.`ID`
				AND svyaz.IBLOCK_PROPERTY_ID = {$property_id}
				    AND svyaz.VALUE = {$element_id}
				    AND property.DESCRIPTION = 'Цвет'
				    AND property.IBLOCK_PROPERTY_ID = {$color_property_id})
				GROUP BY property.VALUE
				ORDER BY property.DESCRIPTION ASC;
			";
			$s                 = $DB->Query($q);
			while ($t = $s->Fetch()) {
				$result[] = $t;
			}
			return $result;
		}


		/**
		 * @param $element_id
		 * @return array
		 * Получение списка размеров по айдишнику карточки товара
		 */
		static function GetSizeByTovarID($element_id)
		{
			global $DB;
			$result            = array();
			$property_id       = self::$property_id;
			$color_property_id = self::$color_property_id;
			$q                 = "

SELECT
  size.`VALUE`
FROM
  `b_iblock_element_property` AS size,
  `b_iblock_element_property` AS compred
  JOIN `b_catalog_product` AS quant
    ON `quant`.`QUANTITY` > 0
    AND quant.`ID` = compred.`IBLOCK_ELEMENT_ID`
WHERE compred.`IBLOCK_PROPERTY_ID` = {$property_id}
  AND compred.`VALUE` = {$element_id}
  AND `compred`.`IBLOCK_ELEMENT_ID` = size.`IBLOCK_ELEMENT_ID`
   AND size.`DESCRIPTION` = 'Размер'
   GROUP BY size.`VALUE`
";
			$s                 = $DB->Query($q);
			while ($t = $s->Fetch()) {
				$result[] = $t;
			}
			return $result;
		}


	}
