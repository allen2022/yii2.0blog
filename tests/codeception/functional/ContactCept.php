<?php

use tests\codeception\_pages\ContactPage;

/* @var $scenario Codeception\Scenario */

$I = new FunctionalTester($scenario);
$I->wantTo('ensure that contact works');

$contactPage = ContactPage::openBy($I);

$I->see('Contact', 'h1');

$I->amGoingTo('submit contact form with no data');
$contactPage->submit([]);
$I->expectTo('see validations errors');
$I->see('Contact', 'h1');
$I->see('Name cannot be blank');
$I->see('Email cannot be blank');
$I->see('Subject cannot be blank');
$I->see('Body cannot be blank');
$I->see('The verification coder is incorrect');

$I->amGoingTo('submit contact form with not correct email');
$contactPage->submit([
    'name'			=>	'tester',
    'email'			=>	'tester.email',
    'subject'		=>	'test subject',
    'body'			=>	'test content',
    'verifyCode'	=>	'testme',
]);
$I->expectTo('see that email address is wrong');
$I->dontSee('Name cannot be blank', '.help-inline');
$I->see('Email is not a valid email address.');
$I->dontSee('Subject cannot be blank', '.help-inline');
$I->dontSee('Body cannot be blank', '.help-inline');
$I->dontSee('The verification coder is incorrect', '.help-inline');

$I->amGoingTo('submit contact form with correct data');
$contactPage->submit([
    'name'			=>	'tester',
    'email'			=>	'tester@example.com',
    'subject'		=>	'test subject',
    'body'			=>	'test content',
    'verifyCode'	=>	'testme',
]);
$I->dontSeeElement('#contact-form');
$I->see('Thank you for contacting us. We will respond to you as soon as possible.');
