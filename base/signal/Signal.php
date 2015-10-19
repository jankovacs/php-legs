<?php

/**
 * Created by PhpStorm.
 * User: jan
 * Date: 2015.04.03.
 * Time: 9:54
 */
class Signal implements ISignal
{
    private $handlers;

    public function __construct()
    {
        $this->handlers = [ ];
    }

    final public function dispatch( $payload )
    {
        foreach ( $this->handlers as $signalHandlerVO )
        {
            call_user_func( [ $signalHandlerVO->classObject, $signalHandlerVO->callback ],
                [ get_class( $this ), $payload ] );
        }
    }

    final public function add( $classObject, $callback )
    {
        $signalHandlerVO = new SignalHandlerVO();
        $signalHandlerVO->classObject = $classObject;
        $signalHandlerVO->callback = $callback;
        $this->handlers[ ] = $signalHandlerVO;
    }

    final public function remove( $classObject, $callback )
    {
        foreach ( $this->handlers as $key => $signalHandlerVO )
        {
            if ( $signalHandlerVO->classObject == $classObject && $signalHandlerVO->callback == $callback )
            {
                array_splice( $this->handlers, $key );
                break;
            }
        }
    }

    final public function removeAll()
    {
        $this->handlers = [ ];
    }
}