<?php



class DataBase
{

    const MySQL = 'MySQL'; // MySQL, Postgre
    const Postgre = 'Postgre';
    private $type;
    private $name;
    private $db;

    function __construct( $type = "", $host = "", $user = "", $pwd = "", $name = "" )
    {

        $this->setType( $type );

        if ( !empty( $host ) && !empty( $user ) && !empty( $pwd ) && !empty( $name ) )
        {
            $this->connect( $host, $user, $pwd, $name );
        }
    }

    public function setType( $type )
    {
        $this->type = $type;
    }

    public function connect( $host, $user, $pwd, $name )
    {
        switch ( $this->type )
        {
            case DataBase::MySQL:
                if ( function_exists( 'mysqli_connect' ) )
                {
                    $this->db = new MySQLiClass( $host, $user, $pwd, $name );
                } else
                {
                    $this->db = new MySQL();
                    $this->db->connect( $name, $host, $user, $pwd );
                }
                $this->db->query( "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'" );
                break;
            case DataBase::Postgre:
                $this->db = new PostgreSQL( "host=" . $host . " dbname=" . $name . " user=" . $user . " password=" . $pwd . "" );
                break;
            default:
                throw new Exception( "Could not select connection type!" );
                break;
        }
    }

    /*
     * Magic functions
     */

    public function __get( $property )
    {
        if ( property_exists( $this->db, $property ) )
        {
            return $this->db->$property;
        }
    }

    public function __set( $property, $value )
    {
        if ( property_exists( $this->db, $property ) )
        {
            $this->db->$property = $value;
        }

        return $this;
    }

    public function __call( $name, $arguments )
    {
        if ( !$this->db )
        {
            return null;
        }

        if ( count( $arguments ) == 0 )
        {
            if ( property_exists( $this->db, $name ) )
            {
                return $this->db->$name;
            } else
            {
                if ( method_exists( $this->db, $name ) )
                {
                    return $this->db->$name();
                } else
                {
                    return null;
                }
            }
        } else
        {
            return call_user_func_array( array( $this->db, $name ), $arguments );// $this->db->$name($arguments[0]);
        }
    }
}

?>
