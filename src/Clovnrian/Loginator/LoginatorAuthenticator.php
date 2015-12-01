<?php

namespace Clovnrian\Loginator;

use Nette\Security as NS;
use Nette\Object;
use Nette\Database\Context;
use Clovnrian\Loginator\Exceptions\BadConfiguration;
use Nette\Database\DriverException;

class LoginatorAuthenticator extends Object implements NS\IAuthenticator
{
  /** @var Context */
  public $database;

  /** @var array */
  private $config;

  /**
   * @param Context     $database
   */
  function __construct(Context $database)
  {
    $this->database = $database;
  }

  /**
   * @param array $credentials
   * @return NS\Identity
   * @throws BadConfiguration
   * @throws NS\AuthenticationException
   */
  function authenticate(array $credentials)
  {
    list($username, $password) = $credentials;

    try {
      $row = $this->database->table($this->config['tableName'])
        ->where($this->config['loginColumnName'], $username)->fetch();
    } catch (DriverException $e) {
      throw new BadConfiguration($e->getMessage());
    }

    if (!$row) {
      throw new NS\AuthenticationException('User not found');
    }

    if (!$row->offsetExists($this->config['passwordColumnName'])) {
      throw new BadConfiguration(
        sprintf(
          "Password column \"%s\" wasn't found in table \"%s\"",
          $this->config['passwordColumnName'],
          $this->config['tableName']
        )
      );
    }

    if (!NS\Passwords::verify($password, $row->offsetGet($this->config['passwordColumnName']))) {
      throw new NS\AuthenticationException('Invalid password');
    }

    if (!$row->offsetExists($this->config['roleColumnName'])) {
      throw new BadConfiguration(
        sprintf(
          "Role column \"%s\" wasn't found in table \"%s\"",
          $this->config['roleColumnName'],
          $this->config['tableName']
        )
      );
    }

    return new NS\Identity($row->id, $row->offsetGet($this->config['roleColumnName']), $row->toArray());
  }
}