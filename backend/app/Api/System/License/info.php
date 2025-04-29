<?php

use MyMythicalID\App;
use MyMythicalID\Chat\LicenseKey\LicenseKey;
use MyMythicalID\Chat\Project\MythicalDashInstance;
use MyMythicalID\Chat\Project\Project;

$router->get('/api/system/license/(.*)/info', function (string $licenseKey): void {
	App::init();
	$appInstance = App::getInstance(true);
	$appInstance->allowOnlyGET();

	$data = $appInstance->addLicenseCheck($licenseKey, $appInstance);

	$appInstance->OK('MythicalZero', ['data' => $data]);
});	