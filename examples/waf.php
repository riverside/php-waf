<?php
include __DIR__ . '/../src/autoload.php';
$waf = new \Riverside\Waf\Firewall();
$waf->setLogFile('C:\Users\riverside\Documents\GitHub\php-waf\waf.log');
$waf->run();