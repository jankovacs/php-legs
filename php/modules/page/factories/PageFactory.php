<?php

/**
 * Created by PhpStorm.
 * User: jan
 * Date: 2015.04.05.
 * Time: 10:12
 */
class PageFactory
{

    public function createPage( $pageName )
    {
        switch ( $pageName )
        {
            case 'index':
                return new IndexPage();
                break;

            default:
                return new PageNotFound();
                break;
        }
    }
}