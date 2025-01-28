<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("testCurrency");
?>
<?$APPLICATION->IncludeComponent(
	"ttmedia:exchange.rates.filter",
	"",
	Array(
		"FILTER_NAME" => "curFilter",
	)
);?>
<?$APPLICATION->IncludeComponent(
	"ttmedia:exchange.rates.list",
	"",
	Array(
		"DISPLAY_NAV" => "Y",
		"ROWS_COUNT" => 10,
		"SHOW_FIELDS" => ["code", "course"],
        "FILTER_NAME" => "curFilter",
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>