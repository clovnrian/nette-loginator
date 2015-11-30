<?php

namespace Clovnrian\Loginator\Components;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Localization\ITranslator;

class SignInControl extends Control
{
  /** @var string */
  private $templatePath = "sign-in.latte";

  /** @var ITranslator */
  private $translator;

  /** @var array */
  public $onSuccess;

  /** @var array */
  public $onError;

  /** @var array */
  public $onValidate;

  /** @var array */
  public $onSubmit;

  /**
   * @param ITranslator $translator
   */
  public function __construct(ITranslator $translator)
  {
    $this->translator = $translator;
  }


  public function render()
  {
    $this->template->setFile($this->templatePath);
    $this->template->render();
  }

  public function createComponentSignInForm()
  {
    $form = new Form();

    $form->addText('name', 'loginator.signIn.labels.name');
    $form->addPassword('password', 'loginator.signIn.labels.password');
    $form->addSubmit('login', 'loginator.signIn.labels.signIn');

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

  }

  public function formValidate(Form $form)
  {

  }

  public function formError(Form $form)
  {

  }
}