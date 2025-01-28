<?php
\Bitrix\Main\Loader::registerAutoLoadClasses('exchange.rates', array(
	'\Rates\Agents\UpdateRatesAgent' 		=>	'lib/agents/updateratesagent.php',
	'\Rates\Entity\RatesTable' 		=>	'lib/entity/ratestable.php',
));