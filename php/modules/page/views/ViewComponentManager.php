<?php

/**
 * Created by PhpStorm.
 * User: jan
 * Date: 2015.04.06.
 * Time: 8:57
 */
class ViewComponentManager
{
    private $componentOnPlace;
    private $registerViewComponent;

    public function __construct()
    {
        $this->componentOnPlace = new ComponentOnPlace();
        $this->registerViewComponent = new RegisterViewComponent();
    }

    public function addTemplateToPlace( $place, $template )
    {
        $this->registerViewComponent->register( $place, $template );
    }

    public function addComponentToPlace( $place, $component )
    {
        $this->componentOnPlace->add( $place, $component );
    }

    public function renderComponentsOnPlaces()
    {
        $this->componentOnPlace->render();
    }
}