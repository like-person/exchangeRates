<?php
/** @global CUser $USER */
/** @global CMain $APPLICATION */
/** @global string $mid */
use Bitrix\Main\Localization\Loc,
	Bitrix\Main\HttpApplication,
	Bitrix\Main\Loader,
	Bitrix\Main\Config\Option;

$request = HttpApplication::getInstance()->getContext()->getRequest();
$module_id = htmlspecialchars($request['mid'] != '' ? $request['mid'] : $request['id']);

if(!$USER->IsAdmin())
	return;

if (!Loader::includeModule($module_id))
	return;
	
Loc::loadMessages(__FILE__);
	

	
	$aTabs = array(
		array(
			/*
			 * Первая вкладка «Основные настройки»
			 */
			'DIV'     => 'edit1',
			'TAB'     => Loc::getMessage('OPTIONS_TAB_GENERAL'),
			'TITLE'   => Loc::getMessage('OPTIONS_TAB_GENERAL'),
			'OPTIONS' => array(
				array(
					'switch_on',                                 
					Loc::getMessage('OPTIONS_TAB_CURRENCY_LIST'), 
					'',                                           
					array('textarea', 5, 20)                              
				),
			)
		),
	);
	
	$tabControl = new CAdminTabControl(
		'tabControl',
		$aTabs
	);
	
	$tabControl->begin();
	?>
	<form action="<?= $APPLICATION->getCurPage(); ?>?mid=<?=$module_id; ?>&lang=<?= LANGUAGE_ID; ?>" method="post">
		<?= bitrix_sessid_post(); ?>
		<?php
		foreach ($aTabs as $aTab) { // цикл по вкладкам
			if ($aTab['OPTIONS']) {
				$tabControl->beginNextTab();
				__AdmSettingsDrawList($module_id, $aTab['OPTIONS']);
			}
		}
		$tabControl->buttons();
		?>
		<input type="submit" name="apply" 
			   value="<?= Loc::GetMessage('INPUT_APPLY'); ?>" class="adm-btn-save" />
	</form>
	
	<?php
	$tabControl->end();
	
	if ($request->isPost() && check_bitrix_sessid()) {
	
		foreach ($aTabs as $aTab) { // цикл по вкладкам
			foreach ($aTab['OPTIONS'] as $arOption) {
				if (!is_array($arOption)) { 
					continue;
				}
				if ($arOption['note']) {
					continue;
				}
				if ($request['apply']) { 
					$optionValue = $request->getPost($arOption[0]);
					Option::set($module_id, $arOption[0], is_array($optionValue) ? implode(',', $optionValue) : $optionValue);
				}
			}
		}
	
		LocalRedirect($APPLICATION->getCurPage().'?mid='.$module_id.'&lang='.LANGUAGE_ID);
	
	}