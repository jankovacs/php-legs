<?php

    chdir(dirname(__DIR__));
    require_once 'framework/core/utils/autoloader/AutoLoader.php';

    $autoLoader = new AutoLoader();

    $engine = new Engine();
    $engine->addCommandConfig(new CommandConfigurator());
    $engine->addRoutingConfig(new RoutingConfigurator());
    $engine->setSmarty();

?>