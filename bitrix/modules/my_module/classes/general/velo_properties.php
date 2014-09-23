<?

	class velo_properties
	{
		public static $types = array( //мнемонические коды свойств инфоблока
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


		static $prop_id = '';

		static $eges = array( //размеры дисков по возростам детей
				'от 1,5 до 3 лет' => array(
						'12"'
				),
				'от 3 до 5 лет' => array(
						'14"',
						'16"'
				),
				'от 5 до 10 лет' => array(
						'18"',
						'20"'
				),
		);
		public $result = array();


		static function GetVeloTypes()
		{
			$iblock_id = velo::$Iblock_id;
			global $DB;
			$result = array();
			$q      = "
			SELECT
			  `real_prop`.`NAME`,`real_prop`.`CODE`,`real_prop`.`ID`
			FROM
			  b_iblock_element AS element
			    INNER JOIN `b_iblock_element_property` AS `prop`
			  ON(`prop`.`IBLOCK_ELEMENT_ID` = `element`.`ID` AND `prop`.`VALUE` = 'да')

			  INNER JOIN `b_iblock_property` AS real_prop
			  ON(`real_prop`.`ID` = `prop`.`IBLOCK_PROPERTY_ID`)
			  WHERE
			  `element`.`IBLOCK_ID` = {$iblock_id}
			  GROUP BY `real_prop`.`ID`
			";
			$res    = $DB->Query($q);
			while ($t = $res->Fetch()) {
				$result[$t['NAME']] = $t['CODE'];
			}
			self::$types = $result;
			return $result;
		}

		public function velo_properties()
		{
			self::GetVeloTypes();
			foreach (velo_config::$proprs as $key => $vol) {
				$this->result[$vol['CODE']] = $this->getPropsUniq($vol['ID'], $vol['TYPE_HTML']);
			}

		}

		static function GetPropIDByCode($code)
		{
			global $DB;
			$iblock_id = Velo::$Iblock_id;
			$prop_id   = $DB->Query("SELECT ID FROM b_iblock_property WHERE CODE = '{$code}' AND IBLOCK_ID = {$iblock_id}")
					->Fetch();
			return $prop_id;
		}


		/**
		 * @param $prop_code
		 * @return mixed
		 * Возвращает Название(подпись) свойства инфоблока, по его мнемоническому коду
		 */
		static function GetNamePropsByPropsCode($prop_code)
		{
			global $DB;
			$q   = "
				SELECT
		          prop.NAME
		        FROM
		         b_iblock_property as prop
		         WHERE
		           prop.CODE = '{$prop_code}'
		        ";
			$res = $DB->Query($q)
					->Fetch();
			return $res['NAME'];
		}

		/**
		 * @param $prop_id
		 * @return array
		 * Возвращает массив уникальных значений свойства по его id
		 */
		public function getPropsUniq($prop_id, $type)
		{
			global $DB;
			$result = array();
			$q      = "
				SELECT
		          prop.VALUE, prop.ID
		        FROM
		         b_iblock_element_property as prop
		         WHERE
		           prop.IBLOCK_PROPERTY_ID = '{$prop_id}'
		         GROUP BY
		           prop.VALUE
		        ";
			$res    = $DB->Query($q);
			while ($t = $res->Fetch()) {
				$t['type'] = $type;
				$result[]  = $t;
			}
			return $result;

		}


	}


