<?php

/**
 * Created by PhpStorm.
 * User: jan
 * Date: 2015.04.03.
 * Time: 9:48
 */
namespace base\signal;

interface ISignal
{
    function dispatch( $payload );

    function add( $classObject, $callback );

    function remove( $classObject, $callback );

    function removeAll();
}