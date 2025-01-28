<?php
namespace Rates\Entity;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\ORM\Fields\FloatField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;

/**
 * Class RatesTable
 * 
 * Fields:
 * <ul>
 * <li> id int mandatory
 * <li> code string(255) optional
 * <li> date datetime optional
 * <li> course double optional
 * </ul>
 *
 * @package Rates\Entity
 **/

class RatesTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'exchange_rates';
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap()
	{
		return [
			'id' => new IntegerField(
				'id',
				[
					'primary' => true,
					'autocomplete' => true,
					'title' => Loc::getMessage('RATES_ENTITY_ID_FIELD'),
				]
			),
			'code' => new StringField(
				'code',
				[
					'validation' => function()
					{
						return[
							new LengthValidator(null, 255),
						];
					},
					'title' => Loc::getMessage('RATES_ENTITY_CODE_FIELD'),
				]
			),
			'date' => new DatetimeField(
				'date',
				[
					'title' => Loc::getMessage('RATES_ENTITY_DATE_FIELD'),
				]
			),
			'course' => new FloatField(
				'course',
				[
					'title' => Loc::getMessage('RATES_ENTITY_COURSE_FIELD'),
				]
			),
		];
	}
}