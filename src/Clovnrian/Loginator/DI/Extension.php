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
    ],
    'signIn' => [
      'templatePath' => 'sign-in.latte',
      'nameField' => [
        'label' => 'Username:',
        'requireText' => 'Please enter your username.'
      ],
      'passwordField' => [
        'label' => 'Password:',
        'requireText' => 'Please enter your password.'
      ],
      'rememberField' => [
        'label' => 'Keep me signed in',
      ],
      'submitText' => 'Sign in'
    ],
  ];

  public function loadConfiguration()
  {
    $config = $this->getConfig($this->defaultConfig);
    $builder = $this->getContainerBuilder();

    $this->compiler->parseServices($builder, $this->loadFromFile(__DIR__ . '../config/loginator.neon'), $this->name);

    $builder->getDefinition($this->prefix('authenticator'))
      ->addSetup('setConfig', [$config['database']]);

    $builder->getDefinition($this->prefix('signInControlFactory'))
      ->addSetup('setTemplatePath', [$config['signIn']['templatePath']])
      ->addSetup('setFormConfig', [$config['signIn']]);
  }
}