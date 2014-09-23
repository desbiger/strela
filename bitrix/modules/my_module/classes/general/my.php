<?
	class my
	{
		/**
		 * @var int
		 * Айди инфоблока книг
		 */
		static $_books_iblock_id = 1;
		/**
		 * @var int
		 * Айди инфблока рейтингов
		 */
		static $_reyting_iblock_id = 2;
		/**
		 * @var int
		 * Айди инфоблока жанров
		 */
		static $_ganr_iblock_id = 3;
		/**
		 * @var int
		 * Айди инфоблока баннеров
		 */
		static $_banner_iblock_id = 5;


		/**
		 * @param $iblock_id
		 * Получаем список кодов дополнительных полеЙ для инфоблока с ID = $iblock_id
		 */
		static function GetPropdertyCodesByIblockID($iblock_id)
		{
			$result = array();
			$q      = "
			SELECT CODE FROM
			b_iblock_property
			WHERE
			ACTIVE = 'Y' AND
			 IBLOCK_ID = '{$iblock_id}'
			";
			global $DB;
			$res = $DB->Query($q);
			while ($t = $res->Fetch()) {
				$result[] = $t['CODE'];
			}
			return $result;
		}

		/**
		 * Подключаем модуль инфоблоков
		 */
		static function inc()
		{
			CModule::IncludeModule('iblock');
		}

		static function ReturnData($timestamp)
		{
			$date = date('d.m.Y G:h', $timestamp);
			$now  = date('d.m.Y G:h');
		}


		static function GetHotObjavs()
		{
			self::inc();
			$filter = array(
				'IBLOCK_ID' => 5,
				'PROPERTY' => array(
					'hot' => 5
				),
			);
			$res    = self::Return_array(CIBlockElement::GetList(null, $filter), null, true, array(
				'NAME',
				'ID',
				'DETAIL_PICTURE',
				'PREVIEW_PICTURE',
				'PREVIEW_TEXT'
			), false);
			return $res;
		}

		static function GetSections($iblock_id)
		{
			self::inc();
			$filter = array(
				'IBLOCK_ID' => $iblock_id,
				'DEPTH_LEVEL' => 1,
			);
			$res    = self::Fetch(CIBlockSection::GetList(null, $filter), false, array(
				'NAME',
				'ID'
			));
			foreach ($res as &$vol) {
				$vol['SUB'] = self::GetSubsections($vol['ID']);
			}
			return $res;
		}

		static function GetElementsCount($section_id)
		{
			self::inc();
			$filter = array(
				'IBLOCK_ID' => 5,
				'SECTION_ID' => $section_id
			);
			$res    = self::Return_array(CIBlockElement::GetList(null, $filter), null, true, null, false);
			return count($res);
		}

		static function GetSectionIDByName($name, $subsection)
		{
			self::inc();
			$result = false;
			$filter = array(
				'IBLOCK_ID' => 5,
				'SECTION_ID' => $subsection,
				'NAME' => $name
			);
			$CDB    = CIBlockSection::GetList(null, $filter);
			$result = self::Return_array($CDB, null, true, null, false);
			if (count($result) > 1) {
				foreach ($result as $node) {
					$result[] = $node['ID'];
				}
				return $result;
			}
			else {
				return $result[0]['ID'];
			}

		}

		static function GetSubsections($cur_section_id)
		{
			$filter = array(
				'SECTION_ID' => $cur_section_id,
			);
			$res    = self::Fetch(CIBlockSection::GetList(null, $filter), false, array(
				'NAME',
				'ID'
			));
			foreach ($res as &$vol) {
				$vol['SUB'] = self::GetSubsections($vol['ID']);
			}
			return $res;
		}

		/**
		 * @param $CDBResult
		 * @param bool $check_count
		 * @return array
		 * Возвращает массив данных
		 */
		static function Fetch($CDBResult, $check_count = false, $fields = false)
		{
			while ($res = $CDBResult->fetch()) {
				if (is_array($fields)) {
					$resul = array();
					foreach ($fields as $vol) {
						$resul[$vol] = $res[$vol];
					}
					$result[] = $resul;
				}
				else {
					$result[] = $res;
				}

			}
			if (count($result) == 1 && $check_count) {
				return $result[0];
			}
			else {
				return $result;
			}
		}

		static function ConvertToSelectArray($array)
		{
			foreach ($array as $vol) {
				$temp[$vol['ID']] = $vol['VALUE'] == '' ? $vol['NAME'] : $vol['VALUE'];
			}
			return $temp;
		}

		static function GetVKLikes($element_id)
		{
			$vk            = new vkapi('3317273', 'zWKknj5n3cMpRnxvmuUu');
			$url           = 'http://imho.obed46.ru/frame/' . $element_id . '/';
			$page_vk_likes = json_decode($vk->api('likes.getList', array(
				'type' => 'sitepage',
				'owner_id' => '3317273',
				'page_url' => $url
			)), true);
			return $page_vk_likes['response']['count'];
		}


		/**
		 * @param $CDBResult объект CDBResult
		 * @param array $prop массив значений свойств которые надо получить
		 * @param bool $prop_as_vol выводить только значения
		 * @param array $fields массив значений которые надо вывести
		 * @param bool $check_count выводить в один массив элемент если он только один
		 * @return array
		 * Функция возвращает массив значений результата работы цикла перебора данных GetNextElement()
		 */
		static function Return_array($CDBResult, $prop = array(), $prop_as_vol = true, $fields = array(), $check_count = true)
		{
			while ($t = $CDBResult->GetNextElement()) {
				if (count($fields) > 0) {
					$element = $t->GetFields();
					foreach ($fields as $vol) {
						$result[$vol] = $element[$vol];
					}
				}
				else {
					$result = $t->GetFields();
				}
				if (CModule::IncludeModule('catalog')) {
					$price           = CPrice::GetBasePrice($result['ID']);
					$result['PRICE'] = $price;
				}

				if (count($prop) > 0) {
					$element_prop = $t->GetProperties();
					foreach ($prop as $vol) {
						$result['PROP'][$vol] = $element_prop[$vol]['VALUE'];
					}
				}
				else {
					if ($prop_as_vol == true) {
						$result['PROP'] = $t->GetProperties();
						foreach ($result['PROP'] as $key => &$value) {
							$value = $value['VALUE'];
						}
					}
					else {
						$result['PROP'] = $t->GetProperties();
					}

				}
				$return[] = $result;

			}
			if (count($return) == 1 && $check_count) {
				return $return[0];
			}
			else {
				return $return;
			}
		}
	}
