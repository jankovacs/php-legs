<?php

namespace phplegs\base\signal;

interface ISignal
{
    function dispatch( $payload );

    function add( $classObject, $callback );

    function remove( $classObject, $callback );

    function removeAll();
}