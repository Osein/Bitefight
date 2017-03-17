<?php
/**
 * @var \Codeception\Scenario $scenario
 */
$I = new FunctionalTester($scenario);
$I->wantTo('open index page of site');
$I->amOnPage('/');
$I->see('Play now for free!', 'a');
$I->see('Speed server x4', 'div');
$I->seeCurrentUrlEquals('/');