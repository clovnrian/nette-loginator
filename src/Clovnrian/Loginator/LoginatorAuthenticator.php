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
      $row = $this->database->table('users')
        ->where('username', $username)->fetch();
    } catch (DriverException $e) {
      throw new BadConfiguration($e->getMessage());
    }

    if (!$row) {
      throw new NS\AuthenticationException('User not found');
    }

    if (!$row->offsetExists('password'))
      throw new BadConfiguration("Password column wasn't found");

    if (!NS\Passwords::verify($password, $row->offsetGet('password'))) {
      throw new NS\AuthenticationException('Invalid password');
    }

    if (!$row->offsetExists('role'))
      throw new BadConfiguration("Role column wasn't found");

    return new NS\Identity($row->id, $row->offsetGet('role'), $row->toArray());
  }
}