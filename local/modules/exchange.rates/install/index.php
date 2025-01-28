<?php

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\Loader,
	Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

class exchange_rates extends CModule
{
	var $MODULE_ID = "exchange.rates";
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $MODULE_AGENT = "Rates\Agents\UpdateRatesAgent::run();";

	var $errors;

	function __construct()
	{
		$arModuleVersion = array();

		include(__DIR__.'/version.php');

		if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion))
		{
			$this->MODULE_VERSION = $arModuleVersion["VERSION"];
			$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		}

		$this->MODULE_NAME = Loc::getMessage("RATES_INSTALL_NAME");
		$this->MODULE_DESCRIPTION = Loc::getMessage("RATES_INSTALL_DESCRIPTION");
	}

	function InstallDB()
	{
		global $DB, $APPLICATION;
		$this->errors = false;

		$this->errors = $DB->RunSQLBatch(__DIR__."/db/mysql/install.sql");

		if($this->errors !== false)
		{
			$APPLICATION->ThrowException(implode("", $this->errors));
			return false;
		}

		ModuleManager::registerModule($this->MODULE_ID);

		CAgent::AddAgent(
			$this->MODULE_AGENT,  
			$this->MODULE_ID,                
			"N",                      
			86400
		);

		return true;
	}

	function UnInstallDB($arParams = array())
	{
		global $DB, $APPLICATION;
		$this->errors = false;

		if(!Loader::includeModule($this->MODULE_ID))
			return false;

		$arSql = $arErr = array();
		$this->errors = $DB->RunSQLBatch(__DIR__."/db/mysql/uninstall.sql");

		if($this->errors !== false)
		{
			$APPLICATION->ThrowException(implode("", $this->errors));
			return false;
		}


		CAgent::RemoveAgent($this->MODULE_AGENT,$this->MODULE_ID);

		ModuleManager::unRegisterModule($this->MODULE_ID);

		return true;
	}

	function InstallEvents()
	{
		return true;
	}

	function UnInstallEvents()
	{
		return true;
	}

	function InstallFiles()
	{
		if($_ENV["COMPUTERNAME"]!='BX')
		{
			//CopyDirFiles($_SERVER['DOCUMENT_ROOT'].'/local/modules/iblock/install/admin', $_SERVER['DOCUMENT_ROOT']."/bitrix/admin");
			CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/local/modules/ttmedia/install/components", $_SERVER["DOCUMENT_ROOT"]."/local/components", true, true);
		}
		return true;
	}

	function UnInstallFiles()
	{
		if($_ENV["COMPUTERNAME"]!='BX')
		{
			//DeleteDirFiles($_SERVER["DOCUMENT_ROOT"]."/local/modules/iblock/install/admin", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin");
			DeleteDirFiles($_SERVER["DOCUMENT_ROOT"]."/local/modules/iblock/install/components/", $_SERVER["DOCUMENT_ROOT"]."/local/components");
		}
		return true;
	}


	function DoInstall()
	{
		global $APPLICATION, $obModule;
		if($this->InstallDB()) 
		{
			$this->InstallFiles();
		}
		$obModule = $this;
	}

	function DoUninstall()
	{
		global $APPLICATION, $obModule;
		$this->UnInstallDB();
		$GLOBALS["CACHE_MANAGER"]->CleanAll();
		$this->UnInstallFiles();
		$obModule = $this;
	}
}