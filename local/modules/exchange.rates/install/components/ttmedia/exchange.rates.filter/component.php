<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
use Bitrix\Main\Application;


if($arParams["FILTER_NAME"] == ''|| !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"]))
	$arParams["FILTER_NAME"] = "arrFilter";
$FILTER_NAME = $arParams["FILTER_NAME"];


global ${$FILTER_NAME};
${$FILTER_NAME} = array();

$context = Application::getInstance()->getContext();
$request = $context->getRequest();
$params = ['DATE_FROM', 'DATE_TO', 'COURSE_FROM', 'COURSE_TO', 'CODE'];

if (empty($request->get('del_filter')) && !empty($request->get('set_filter'))) {
	foreach ($params as $param){
		$arResult[$param] = $request->get($param);
	}
	if (!empty($arResult['DATE_FROM'])) {
		${$FILTER_NAME}[">date"] = $arResult['DATE_FROM'].' 00:00:00';
	}
	if (!empty($arResult['DATE_TO'])) {
		${$FILTER_NAME}["<date"] = $arResult['DATE_TO'].' 23:59:59';
	}
	if (!empty($arResult['COURSE_FROM'])) {
		${$FILTER_NAME}[">=course"] = $arResult['COURSE_FROM'];
	}
	if (!empty($arResult['COURSE_TO'])) {
		${$FILTER_NAME}["<=course"] = $arResult['COURSE_TO'];
	}
	if (!empty($arResult['CODE'])) {
		${$FILTER_NAME}["=code"] = $arResult['CODE'];
	}
}

$server = $context->getServer();
$arResult["FORM_ACTION"] = htmlspecialcharsbx($server->getRequestUri());
$arResult["FILTER_NAME"] = $FILTER_NAME;

$this->IncludeComponentTemplate();