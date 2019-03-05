<?php


use \CIBlockElement;

/**
 * Вспомогательный класс для работы с элементами инфоблока
 */
class ElementIblock
{
	//кеширование данных
	const CACHE = true;
	const CACHE_TIME = 2678400;
	

	/**
	 * Получение списка значений справочника
	 *
	 * @param string directory - код справочника по параметру arIblockId этого класса
	 * @param array arAddOrder - Массив дополнительных сортировок
	 * @param array arAddFilter - Массив дополнительных фильтров

	 * @return array - массив значений
	*/

	public static function getList(string $directory, array $arAddOrder = null, array $arAddFilter = null, array $arAddSelect = null)
	{
		$arItems = array();

		$cache = Cache::createInstance(); 
		$key = "list_" . $iblockId . "_" . json_encode($arAddOrder) . "_" . json_encode($arAddFilter);
		if ($cache->initCache(self::CACHE_TIME, $key, "/dev/AdsDirectory") && self::CACHE) { 
			$arItems = $cache->getVars();
		} elseif ($cache->startDataCache() || !self::CACHE) {
			if (!Loader::includeModule("iblock")) {
				$cache->abortDataCache();
				return false;
			}

			$arOrder = array(
				"NAME" => "ASC",
				"ID" => "ASC",
			);
			if ($arAddOrder)
				$arOrder = array_merge($arAddOrder, $arOrder);
			else
				$arOrder = array_merge(array("SORT" => "ASC"), $arOrder);

			$arFilter = Array(
				"IBLOCK_ID" => $iblockId,
				"ACTIVE" => "Y",
			);
			if ($arAddFilter)
				$arFilter = array_merge($arFilter, $arAddFilter);

			$arSelect = array(
				"ID"				
			);
			//Добавляем к селекту доп.поля
			$arSelect = array_merge($arSelect, $arAddSelect);
			$res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
			while ($arFields = $res->GetNext()) {				
				$arItems[] = $arFields;
			}
			
			$cache->endDataCache($arItems); 
		}

		return $arItems;
	}
}
