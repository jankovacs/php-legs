<?php
namespace phplegs\base\config;

use injector\api\IInjector;
use phplegs\mapper\factory\MapperFactory;

class BaseMappingConfigurator implements IConfig
{
    /**
     * @var IMapper
     */
    protected $mapper;

    /**
     * @var IInjector
     */
    protected $injector;

    public function __construct()
    {
        $this->mapper = MapperFactory::getMapper( $this->getMapperType() );
    }

    protected function setMappings()
    {
        // Implement setMappings() method.
    }

    protected function getMapperType()
    {
        return '';
    }

    /**
     * @return mixed
     */
    public function getMappings()
    {
        $this->setMappings();
        return $this->mapper->getMappings();
    }

    /**
     * @param IInjector $injector
     */
    public function setInjector(IInjector $injector)
    {
        $this->injector = $injector;
    }
}