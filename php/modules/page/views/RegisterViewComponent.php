<?php

/**
 * Created by PhpStorm.
 * User: jan
 * Date: 2015.04.05.
 * Time: 23:00
 */
class RegisterViewComponent
{
    public function register( $place, $template )
    {
        $smarty = Singleton::getInstance( Smarty::class );
        ob_start();
        $smarty->display( __DIR__ . '/../../' . $template );
        $smarty->assign( $place, ob_get_clean() );
    }
}