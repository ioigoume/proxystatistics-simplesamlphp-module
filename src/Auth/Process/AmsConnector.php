<?php

namespace SimpleSAML\Module\proxystatistics\Auth\Process;

use SimpleSAML\Configuration;
use SimpleSAML\Database;
use SimpleSAML\Logger;

class AmsConnector
{
  private $mode;
  private $idpEntityId;
  private $idpName;
  private $spEntityId;
  private $spName;
  private $detailedDays;
  private $userIdAttribute;
  private $conn = null;
  private $oidcIss;
  private $keycloakSp;

  const CONFIG_FILE_NAME = 'module_statisticsproxy.php';

  /** @deprecated */
  const ENCRYPTION = 'encryption';
  const STORE = 'store';
  /** @deprecated */
  const SSL_CA = 'ssl_ca';
  /** @deprecated */
  const SSL_CERT = 'ssl_cert_path';
  /** @deprecated */
  const SSL_KEY = 'ssl_key_path';
  /** @deprecated */
  const SSL_CA_PATH = 'ssl_ca_path';
  const MODE = 'mode';
  const IDP_ENTITY_ID = 'idpEntityId';
  const IDP_NAME = 'idpName';
  const SP_ENTITY_ID = 'spEntityId';
  const SP_NAME = 'spName';
  const DETAILED_DAYS = 'detailedDays';
  const USER_ID_ATTRIBUTE = 'userIdAttribute';
  const OIDC_ISS = 'oidcIssuer';
  const KEYCLOAK_SP = 'keycloakSp';
  const TABLE_PREFIX = 'database.prefix';
  const  AMS_INJEST_ENDPOINT = '/ams/ingest';

  public function __construct()
  {
    $conf = Configuration::getConfig(self::CONFIG_FILE_NAME);
    $this->storeConfig = $conf->getArray(self::STORE, null);

    $this->storeConfig = Configuration::loadFromArray($this->storeConfig);
    $this->databaseDsn = $this->storeConfig->getString('database.dsn');

    $this->mode = $conf->getString(self::MODE, 'PROXY');
    $this->idpEntityId = $conf->getString(self::IDP_ENTITY_ID, '');
    $this->idpName = $conf->getString(self::IDP_NAME, '');
    $this->spEntityId = $conf->getString(self::SP_ENTITY_ID, '');
    $this->spName = $conf->getString(self::SP_NAME, '');
    $this->detailedDays = $conf->getInteger(self::DETAILED_DAYS, 0);
    $this->userIdAttribute = $conf->getString(self::USER_ID_ATTRIBUTE, null);
    $this->oidcIss = $conf->getString(self::OIDC_ISS, null);
    $this->keycloakSp = $conf->getString(self::KEYCLOAK_SP, null);
  }

  public function getConnection()
  {
    return Database::getInstance($this->storeConfig);
  }

  public function getMode()
  {
    return $this->mode;
  }

  public function getIdpEntityId()
  {
    return $this->idpEntityId;
  }

  public function getIdpName()
  {
    return $this->idpName;
  }

  public function getSpEntityId()
  {
    return $this->spEntityId;
  }

  public function getSpName()
  {
    return $this->spName;
  }

  public function getDetailedDays()
  {
    return $this->detailedDays;
  }

  public function getUserIdAttribute()
  {
    return $this->userIdAttribute;
  }

  public function getOidcIssuer()
  {
    return $this->oidcIss;
  }

  public function getKeycloakSp()
  {
    return $this->keycloakSp;
  }
}