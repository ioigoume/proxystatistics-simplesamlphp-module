<?php

namespace SimpleSAML\Module\proxystatistics\Auth\Process;

use DateTime;
use DateTimeZone;
use SimpleSAML\Auth\ProcessingFilter;
use SimpleSAML\Configuration;
use SimpleSAML\Error\Exception;
use SimpleSAML\Logger;

/**
 *
 * @author Pavel Vyskočil <vyskocilpavel@muni.cz>
 */
class Statistics extends ProcessingFilter
{
    private $config;
    private $reserved;
    private $userIdAttribute;

    public function __construct($config, $reserved)
    {
        parent::__construct($config, $reserved);
        $this->config = Configuration::getConfig(DatabaseConnector::CONFIG_FILE_NAME);
        $this->reserved = (array)$reserved;
    }

    public function process(&$request)
    {
        if (empty($this->config->getString('userIdAttribute'))) {
            $this->userIdAttribute = $request['rciamAttributes']['cuid'];
        } else {
            $this->userIdAttribute = $request['Attributes'][$this->config->getString('userIdAttribute')];
        }
        // Check if user is in blacklist
        if (!empty($this->userIdAttribute) && !empty($this->config->getArray('userIdBlacklist')) && !empty(array_intersect(
            $this->userIdAttribute,
            $this->config->getArray('userIdBlacklist')))) {
            Logger::notice("[proxystatistics:proccess] Skipping blacklisted user with id " . var_export($this->userIdAttribute, true));
            return;
        }

        $dateTime = new DateTime('now', new DateTimeZone( 'UTC' ));
        $dbCmd = new DatabaseCommand();
        $dbCmd->insertLogin($request, $dateTime, $this->userIdAttribute);
        $spEntityId = $request['SPMetadata']['entityid'];

        $userIdentity = '';
        $sourceIdPEppn = '';
        $sourceIdPEntityId = '';

        if (isset($this->userIdAttribute[0])) {
            $userIdentity = $this->userIdAttribute[0];
        }
        if (isset($request['Attributes']['sourceIdPEppn'][0])) {
            $sourceIdPEppn = $request['Attributes']['sourceIdPEppn'][0];
        }
        if (isset($request['Attributes']['sourceIdPEntityID'][0])) {
            $sourceIdPEntityId = $request['Attributes']['sourceIdPEntityID'][0];
        }

        if (isset($request['perun']['user'])) {
            $user = $request['perun']['user'];
            Logger::notice('UserId: ' . $user->getId() . ', identity: ' .  $userIdentity . ', service: '
                . $spEntityId . ', external identity: ' . $sourceIdPEppn . ' from ' . $sourceIdPEntityId);
        } else {
            Logger::notice('User identity: ' .  $userIdentity . ', service: ' . $spEntityId .
                ', external identity: ' . $sourceIdPEppn . ' from ' . $sourceIdPEntityId);
        }

    }

}
