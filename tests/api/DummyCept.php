<?php 
$I = new ApiTester($scenario);
$I->wantTo('perform actions and see result');
$I->sendPOST('users/', ['email' => 'angela@moss.com', 'password' => 'string-beans']);
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::CREATED); // 200
$I->sendGET('users/');

$I->seeResponseMatchesJsonType(['email' => 'string', 'id' => 'integer'], '$.data[0]');
