<?php

require 'vendor/autoload.php';

// This would be the url of the host running the server-standalone.jar
$host = 'http://localhost:4444/wd/hub'; // this is the default
$capabilities = array(WebDriverCapabilityType::BROWSER_NAME => 'chrome');
$driver = new RemoteWebDriver($host, $capabilities);

$driver->manage()->timeouts()->implicitlyWait(3);

$driver->get('http://localhost/jquery_ui_page.html');

if ($driver->getTitle() !== 'jQuery UI Example Page')
{
	bail_out($driver, sprintf('Fail: Title did not match, was: %s', $driver->getTitle()));
}
echo 'Pass: Title matches.' . "\n";


// Test Autocomplete Box
$driver->findElement(WebDriverBy::id('countries'))->sendKeys('United');
$driver->findElement(WebDriverBy::linkText('United States'))->click();
$completed_value = $driver->findElement(WebDriverBy::id('countries'))->getAttribute('value');
if ( $completed_value !== 'United States')
{
	bail_out($driver, sprintf('Fail: Confirm clicking on an autocomplete puts in full value, was: %s', $completed_value));
}
echo 'Pass: Autocomplete fills in new value.' . "\n";

// Test Accordion
if (!$driver->findElement(WebDriverBy::id('ui-accordion-1-panel-0'))->isDisplayed())
{
 	bail_out($driver, 'Fail: The first accordion panel is not visable at start.');
}
echo 'Pass: The first accordion panel is visable at start.' . "\n";

if ($driver->findElement(WebDriverBy::id('ui-accordion-1-panel-1'))->isDisplayed())
{
 	bail_out($driver, 'Fail: The second accordion panel is visable at start.');
}
echo 'Pass: The second accordion panel is not visable at start.' . "\n";

$driver->findElement(WebDriverBy::id('ui-accordion-1-header-1'))->click();

if ($driver->findElement(WebDriverBy::id('ui-accordion-1-panel-0'))->isDisplayed())
{
 	bail_out($driver, 'Fail: the first accordion panel is visable after switching to second panel.');
}
echo 'Pass: the first accordion panel is not visable after switching to second panel.' . "\n";

if (!$driver->findElement(WebDriverBy::id('ui-accordion-1-panel-1'))->isDisplayed())
{
 	bail_out($driver, 'Fail: second accordion panel is not visable after switching to second panel.');
}
echo 'Pass: The second accordion panel is visable after switching to second panel.' . "\n";


$driver->quit();

function bail_out($driver, $error)
{
	$driver->quit();
	exit($error . "\n");
}
