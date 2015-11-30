<?php

namespace Clovnrian\Loginator\Components;

interface ISignInControlFactory
{
  /**
   * @return SignInControl
   */
  function create();
}