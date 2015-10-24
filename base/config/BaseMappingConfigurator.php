<?php
namespace base\config;

use mapper\factory\MapperFactory;

class BaseMappingConfigurator implements IConfig
{
    protected $mapper;

    public function __construct()
    {
        $this->mapper = MapperFactory::getMapper( $this->getMapperType() );
        $this->setMappings();
    }

    protected function setMappings()
    {
        // Implement setMappings() method.
    }

    protected function getMapperType()
    {
        return '';
    }

    public function getMappings()
    {
        return $this->mapper->getMappings();
    }
}