<?php

/** @var \Symfony\Component\EventDispatcher\EventDispatcher $dispatcher */
$dispatcher->addListener(\AKlump\Knowledge\Events\GetVariables::NAME, function (\AKlump\Knowledge\Events\GetVariables $event) {
  (new \AKlump\Knowledge\User\InstallWithComposerVariable())($event);
});
