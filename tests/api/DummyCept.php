<?php 
$I = new ApiTester($scenario);
$I->wantTo('perform actions and see result');
$I->sendGET('');
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
