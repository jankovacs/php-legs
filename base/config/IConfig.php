<?php

namespace phplegs\base\config;

use injector\api\IInjector;

interface IConfig
{
    function setInjector(IInjector $injector);
    function getMappings();
} 