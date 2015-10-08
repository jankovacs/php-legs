<?php

/**
 * Created by PhpStorm.
 * User: jan
 * Date: 2015.04.04.
 * Time: 21:09
 */
class RoutingService
{
    private $fileContents;

    public function loadJsonSettings( $jsonFilePath )
    {
        $this->fileContents = file_get_contents( $jsonFilePath );
    }

    public function parse()
    {
        return json_decode( $this->fileContents );
    }
}