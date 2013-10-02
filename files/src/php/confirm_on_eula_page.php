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
	$bail_out($driver, sprintf('Title did not match, was: %s', $title));
}

echo 'Pass: Title matches.' . "\n";


$driver->quit();


function bail_out($driver, $error)
{
	$driver->quit();
	exit($error);
}
