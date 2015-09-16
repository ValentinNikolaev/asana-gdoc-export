<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config.php';

global $gProjectDir;
global $humanTags;
global $credentialsPath;
global $client;
global $clientsProjects;
global $log;
global $report;
global $isCli;
global $emailConfig;

$isCli = php_sapi_name() == "cli";
$report = [
    '<br>',
    '<h1><strong>Process report</h1></strong>',
];
$log = [
    'Process log:'
];

if (DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(-1);
}