<?php

namespace Clovnrian\Loginator\Components\SignIn;

interface ISignInControlFactory
{
  /**
   * @return SignInControl
   */
  function create();
}