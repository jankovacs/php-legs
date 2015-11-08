<?php

namespace phplegs\base\service;

class BaseDataBaseService
{
    protected $db;
    public $disconnectAutomatically;

    public function __construct()
    {
        $this->disconnectAutomatically = false;
        $this->setupDataBaseConnection();
    }

    private function setupDataBaseConnection()
    {
        $this->db = Singleton::getInstance( DataBase::class );
        if ( !$this->db->isConnected() )
        {
            $this->db->setType( DataBaseConfig::TYPE );
            $this->db->connect( DataBaseConfig::HOST, DataBaseConfig::USER, DataBaseConfig::PWD, DataBaseConfig::DB );
        }
    }

    public function __destruct()
    {
        if ( $this->disconnectAutomatically )
        {
            $this->db->disconnect();
        }
    }
}