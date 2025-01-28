<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */



use Bitrix\Main\Loader,
	Rates\Entity\RatesTable,
	Bitrix\Main\UI\PageNavigation;

if($arParams["SHOW_FIELDS"] == '') {
	$arParams["SHOW_FIELDS"] = array();
} else {
	if(!is_array($arParams["SHOW_FIELDS"])) {
		$arParams["SHOW_FIELDS"] = array($arParams["SHOW_FIELDS"]);
	}
}


if($arParams["FILTER_NAME"] == '' || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"]))
{
	$arrFilter = array();
}
else
{
	$arrFilter = $GLOBALS[$arParams["FILTER_NAME"]];
	if(!is_array($arrFilter))
		$arrFilter = array();
}


if($arParams["DISPLAY_NAV"] == 'Y' && $arParams["ROWS_COUNT"] > 0)
{
	$arNavigation = CDBResult::GetNavParams($arNavParams);
	$nav = new PageNavigation("nav-rates");
	$nav->setPageSize($arParams["ROWS_COUNT"])->initFromUri();
}
else
{
	$nav = false;
}
if(!Loader::includeModule("exchange.rates"))
{
	ShowError(GetMessage("MODULE_NOT_INSTALLED"));
	return;
}
$arFields = [];
foreach(RatesTable::getMap() as $key => $fld) {
	$arFields[$key] = GetMessage("FIELD_".strtoupper($key));
}
$arResult["HEADS"] = $arFields;
$arResult["ITEMS"] = array();
$params = ['filter' => $arrFilter];
if ($nav) {
	$params['count_total'] = true;
	$params['offset'] = $nav->getOffset();
	$params['limit'] = $nav->getLimit();
}
$ratesList = RatesTable::getList($params);
if ($nav) {
	$nav->setRecordCount($ratesList->getCount());
}
while ($row = $ratesList->fetch())
{
	$arResult["ITEMS"][] = $row;
}
$arResult["NAV_OBJECT"] = $nav;

$this->includeComponentTemplate();
