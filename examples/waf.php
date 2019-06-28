<?php
include __DIR__ . '/../src/autoload.php';
$waf = new \PhpWaf\Firewall();
$waf->setLogFile('C:\Users\riverside\Documents\GitHub\php-waf\waf.log');
$waf->run();