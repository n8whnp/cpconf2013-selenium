<?php

require 'vendor/autoload.php';

// This would be the url of the host running the server-standalone.jar
$host = 'http://localhost:4444/wd/hub'; // this is the default
$capabilities = array(WebDriverCapabilityType::BROWSER_NAME => 'chrome');
$driver = new RemoteWebDriver($host, $capabilities);

$driver->get('http://localhost/eula.html');

$title = $driver->findElement(WebDriverBy::xpath('html/body/h1'))->getText();

if ($title !== 'End User License Agreement')
{
	bail_out($driver, sprintf('Fail: Title did not match, was: %s', $title));
}
echo 'Pass: Title matches.' . "\n";


if (!$driver->findElement(WebDriverBy::xpath('//textarea'))->isDisplayed())
{
	bail_out($driver, 'Fail: License is not visible');
}
echo 'Pass: License is visible.' . "\n";

if (!$driver->findElement(WebDriverBy::id('agree'))->isDisplayed())
{
	bail_out($driver, 'Fail: Agree button is not visable');
}
echo 'Pass: Agree button is visible.' . "\n";

if (!$driver->findElement(WebDriverBy::id('decline'))->isDisplayed())
{
	bail_out($driver, 'Fail: Disagree button is not visible');
}
echo 'Pass: Disagree button is visible.' . "\n";

// Test the Decline Button

$startUrl = $driver->getCurrentUrl();
$driver->findElement(WebDriverBy::id('decline'))->click();
if ($startUrl !== $driver->getCurrentUrl() )
{
	bail_out($driver, sprintf('Fail: Disagree button took us to a new url: %s', $driver->getCurrentUrl()));
}
echo 'Pass: Disagree button took us back to the same page.' . "\n";

// Test the Agree Button

$startUrl = $driver->getCurrentUrl();
$driver->findElement(WebDriverBy::id('agree'))->click();
if ($startUrl === $driver->getCurrentUrl() )
{
	bail_out($driver, sprintf('Fail: Agree button took us back to the same url: %s', $driver->getCurrentUrl()));
}
echo 'Pass: Agree button took us to a new page.' . "\n";

$confirm_header = $driver->findElement(WebDriverBy::xpath('html/body/h1') )->getText();
if ($confirm_header !== 'Thanks for agreeing')
{
	bail_out($driver, sprintf('Fail: Agree page has wrong header got: %s', $confirm_header));
}
echo 'Pass: Agree page has expected header.' . "\n";

if (!$driver->findElement(WebDriverBy::id('disclaimer'))->isDisplayed())
{
	bail_out($driver, 'Fail: Disclaimer is not visible');
}
echo 'Pass: Disclaimer is visible.' . "\n";


$driver->quit();


function bail_out($driver, $error)
{
	$driver->quit();
	exit($error);
}
