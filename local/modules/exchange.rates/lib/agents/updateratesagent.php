<?php
namespace Rates\Agents;

use Bitrix\Main\Loader,
	Rates\Entity\RatesTable,
	Bitrix\Main\Type\DateTime;

/**
 * Class UpdateRatesAgent for update rates agent
 * 
 *
 * @package Rates\Agents
 **/

class UpdateRatesAgent
{
	/**
	 * @return string
	 * @throws Main\ArgumentNullException
	 */
	public static function run()
	{
		if(Loader::includeModule("exchange.rates"))
		{
			$url = file_get_contents('http://www.cbr.ru/scripts/XML_daily.asp');
			if ($xml = simplexml_load_string($url)) {

				foreach ($xml->Valute as $item) {
					$valute = [
						'code' => strval($item->CharCode),
						'date' => new DateTime(),
						'course' => floatval(str_replace(',', '.', $item->Value)),
					];
					RatesTable::add($valute);
				}
			}
		}

		return "Rates\Agents\UpdateRatesAgent::run();";
	}
}