<?php

namespace Clovnrian\Loginator\Components\SignIn;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Security\User as SUser;
use Nette\Security\AuthenticationException;

class SignInControl extends Control
{
  /** @var string */
  private $templatePath = "sign-in.latte";

  /** @var SUser */
  private $sUser;

  /** @var array */
  public $onSuccess;

  /** @var array */
  public $onError;

  /** @var array */
  public $onValidate;

  /** @var array */
  public $onSubmit;

  /** @var array */
  private $formConfig;

  /**
   * @param SUser $sUser
   */
  public function __construct(SUser $sUser)
  {
    $this->sUser = $sUser;
  }


  public function render()
  {
    $this->template->setFile($this->templatePath);
    $this->template->render();
  }

  public function createComponentSignInForm()
  {
    $form = new Form();

    $form->addText('username', $this->formConfig['nameField']['label'])
      ->setRequired($this->formConfig['nameField']['requireText']);

    $form->addPassword('password', $this->formConfig['passwordField']['label'])
      ->setRequired($this->formConfig['passwordField']['requireText']);

    $form->addCheckbox('remember', $this->formConfig['rememberField']['label']);
    $form->addSubmit('login', $this->formConfig['submitText']);

    $form->onSuccess[] = function($submit) {
      $this->formSuccess($submit->form);
      $this->onSuccess($submit->form);
    };

    $form->onValidate[] = function($submit) {
      $this->formValidate($submit->form);
      $this->onValidate($submit->form);
    };

    $form->onError[] = function($submit) {
      $this->formError($submit->form);
      $this->onError($submit->form);
    };

    $form->onSubmit[] = function($submit) {
      $this->onSubmit($submit->form);
    };

    return $form;
  }

  public function formSuccess(Form $form)
  {
    $values = $form->getValues();

    if ($values->remember) {
      $this->sUser->setExpiration('14 days', FALSE);
    } else {
      $this->sUser->setExpiration('2 hours', TRUE);
    }

    try {
      $this->sUser->login($values->username, $values->password);
    } catch (AuthenticationException $e) {
      $form->addError($e->getMessage());
    }
  }

  public function formValidate(Form $form)
  {

  }

  public function formError(Form $form)
  {

  }
}