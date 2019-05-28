<?php

function class_auto_loader($className)
{
  $parts = explode('\\', $className);
  $path = '/home/trambi/dev_fantasy_football/FantasyFootball/TournamentCoreBundle/' . implode('/', $parts) . '.php';

  require_once $path;
}

spl_autoload_register('class_auto_loader');