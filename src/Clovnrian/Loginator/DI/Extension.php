<?php

namespace Clovnrian\Loginator\DI;

use Nette\DI\CompilerExtension;

class Extension extends CompilerExtension
{

  private $defaultConfig = [
      'database' => [
        'tableName' => 'users',
        'loginColumnName' => 'login',
        'passwordColumnName' => 'password',
        'roleColumnName' => 'role'
      ]
  ];

  public function loadConfiguration()
  {
    $config = $this->getConfig($this->defaultConfig);
    $builder = $this->getContainerBuilder();

    $builder->addDefinition($this->prefix('authenticator'))
      ->setClass('Clovnrian\Loginator\LoginatorAuthenticator')
      ->addSetup('setConfig', [$config['database']]);
  }
}