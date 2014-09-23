<?

	class velo_catalog
	{
		static $types = array( //мнемонические коды свойств инфоблока
				'Горные' => 'GORNYE',
				'Горные женские' => 'GORNYE_ZHENSKIE',
				'Горные двухподвесы' => 'GORNYE_DVUKHPODVESY',
				'Горные 29\'' => 'GORNYE_29',
				'Дорожные' => 'DOROZHNYE',
				'Подростковые' => 'PODROSTKOVYE',
				'Циклокроссовые' => 'TSIKLOKROSSOVYE',
				'Детские' => 'DETSKIE',
				'Шоссейные' => 'SHOSSEYNYE',
				'Складные' => 'SKLADNYE',
				'Гибридные' => 'GIBRIDNYE',
				'Электро' => 'ELEKTRO',
		);

		static $prop_id = 739;

		static $eges = array( //размеры дисков по возростам детей
				'от 1,5 до 3 лет' => array(
						'от 1,5  до 3 лет (12")',
						'12'
				),
				'от 3 до 5 лет' => array(
						'от 3 до 5 лет (14" , 16")',
						'14_16'
				),
				'от 5 до 10 лет' => array(
						'от 5 до 10 лет (18" , 20")',
						'18_20'
				),
		);

		/**
		 * @param $type
		 * @return array
		 */

		static function getBrandsByType($type)
		{

			$result = array();
			global $DB;

			$prop_code = self::$types[$type];
			$prop_id   = self::$prop_id;

			$result[]  = array(
					'VALUE' => 'Все',
					'URL' => '/type/' . $prop_code . "/"
			);
			$q         = "SELECT
		          brand.VALUE,
		          element.ID,
		          prop_type.CODE as CODE
		        FROM
		         b_iblock_element_property as prop,
		         b_iblock_element_property as brand,
		         b_iblock_property as prop_type,
		         b_iblock_element as element
		         WHERE
		           prop_type.CODE = '{$prop_code}' AND
		           prop.IBLOCK_PROPERTY_ID = prop_type.ID AND
		           prop.VALUE IS NOT NULL  AND
		           brand.IBLOCK_PROPERTY_ID = '{$prop_id}' AND
		           element.ID = brand.IBLOCK_ELEMENT_ID AND
		           element.ID = prop.IBLOCK_ELEMENT_ID
		         GROUP BY
		           brand.VALUE
		        ";
			$CDBResult = $DB->Query($q);
			while ($t = $CDBResult->Fetch()) {
				$t['URL'] = "/type/" . $prop_code . "/" . $t['VALUE'] . "/";
				$result[] = $t;
			}
			return $result;
		}


		/**
		 * @return array
		 */
		static function getBrandsByEge($code)
		{
			global $DB;
			$result = array();

			foreach (self::$eges as $key => $vol) {

				$values = str_replace('"', '\"', $vol[0]);
				$prop_id = self::$prop_id;
				$q       = "
				SELECT
				  brand.VALUE,
				  prop_type.CODE AS CODE
				FROM
				  b_iblock_element_property AS vozrast

				  INNER JOIN b_iblock_property AS prop_type
				    ON (
				      vozrast.IBLOCK_PROPERTY_ID = prop_type.ID
				    )
				  INNER JOIN b_iblock_element_property AS brand
				    ON (
				      brand.IBLOCK_ELEMENT_ID = vozrast.IBLOCK_ELEMENT_ID
				    )
				WHERE (
				    vozrast.VALUE =  '{$values}'
				    AND prop_type.CODE = 'VOZRASTNAYA_KATEGORIYA'
				    AND brand.IBLOCK_PROPERTY_ID = '{$prop_id}'
				  )
				GROUP BY brand.VALUE ;



				";

				$CDBResult = $DB->Query($q);
				$brends    = array(
						array(
								'URL' => '/type/DETSKIE/' . str_replace("\"", "", $vol[1] . "/"),
								'VALUE' => 'Все'
						)
				);
				while ($t = $CDBResult->Fetch()) {
					$t['URL'] = "/type/DETSKIE/" . str_replace("\"", "", $vol[1]) . "/" . $t['VALUE'] . "/";
					$brends[] = $t;
				}

				$result[] = array(
						'VALUE' => $key,
						'SUBMENU' => $brends,
						'URL' => "/type/DETSKIE/" . str_replace("\"", "", $vol[1]) . "/"
				);
			}
			//			$result['CODE'] = $code;
			return $result;
		}


		/**
		 * @return array
		 */
		static function getBrends()
		{
			global $DB;
			$prop_id    = self::$prop_id;
			$compred_id = 783;
			$q          = "
		SELECT
		  brend.`VALUE`
		FROM
		  `b_catalog_product` AS q,
		  `b_iblock_element_property` AS brend,
		  `b_iblock_element_property` AS compred
		WHERE
		  q.`QUANTITY` > 0
		  AND compred.`IBLOCK_ELEMENT_ID` = q.`ID`
		  AND compred.`IBLOCK_PROPERTY_ID` = {$compred_id}
		  AND brend.`IBLOCK_ELEMENT_ID` = compred.`VALUE`
		  AND brend.`IBLOCK_PROPERTY_ID` = {$prop_id}

		  GROUP BY brend.`VALUE`";
			$CDBResult  = $DB->Query($q);
			while ($t = $CDBResult->Fetch()) {
				$result[] = $t;
			}
			return $result;
		}


		/**
		 * @param $array
		 * @return string
		 */
		static function GetHtmlMenu($array, $ul = false)
		{
			$str = $ul ? "<ul>" : "";
			foreach ($array as $vol) {
				$str .= "<li>";
				$str .= "<a href='{$vol["URL"]}'>";
				$str .= $vol['VALUE'];
				$str .= "</a>";
				if (count($vol['SUBMENU']) > 0) {
					$str .= self::GetHtmlMenu($vol['SUBMENU'], true);
				}
				$str .= "</li>";
			}
			$str .= $ul ? "</ul>" : "";
			return $str;
		}

	}
