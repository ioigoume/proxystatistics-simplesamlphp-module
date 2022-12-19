<?php

use SimpleSAML\Configuration;
use SimpleSAML\Session;
use SimpleSAML\XHTML\Template;

/**
 * @author Pavel Vyskočil <vyskocilpavel@muni.cz>
 */

$config = Configuration::getInstance();
$session = Session::getSessionFromRequest();

$t = new Template($config, 'proxystatistics:serviceProviders-tpl.php');
$t->data['lastDays'] = filter_input(
    INPUT_GET,
    'lastDays',
    FILTER_VALIDATE_INT,
    ['options' => ['default' => 0, 'min_range' => 0]]
);
$t->data['tab'] = 2;
$t->show();
