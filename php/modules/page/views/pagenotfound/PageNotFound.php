<?php

/**
 * Created by PhpStorm.
 * User: jan
 * Date: 2015.04.05.
 * Time: 10:43
 */
class PageNotFound extends BasePage
{
    public function __construct()
    {
        header( 'HTTP/1.1 404 Not found' );
        parent::__construct();
    }
}