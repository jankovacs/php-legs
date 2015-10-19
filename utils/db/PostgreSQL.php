<?php

class PostgreSQL
{

    const STRING = "string";
    const LITERAL = "literal";
    const BYTEA = "bytea";
    private $connection;
    private $conn_stat;
    private $result;
    private $record;

    /*
     * constructor & desctructor
     */

    function __construct( $connection = null )
    {

        if ( !empty( $connection ) )
        {
            $this->connect( $connection );
        }
    }

    public function connect( $connection )
    {

        if ( empty( $connection ) )
        {
            throw new Exception( "Could not connect to any database without reequired connection data" );
        } else
        {

            $this->connection = pg_connect( $connection );
            $this->conn_stat = pg_connection_status( $this->connection );

            if ( $this->conn_stat != PGSQL_CONNECTION_OK )
            {
                throw new Exception( "Could not connect to database... :(" );
            }
        }
    }

    /*
     * connect & disconnect functions
     */

    function __destruct()
    {
        $this->disconnect();
    }

    public function disconnect()
    {
        if ( $this->connection != null )
        {
            pg_close( $this->connection );
        } else
        {
            pg_close();
        }
    }

    /*
     * query functions
     */

    public function query( $query )
    {
        if ( empty( $query ) )
        {
            throw  new Exception( "Empty query!" );
        } else
        {
            return $this->result = pg_query( $this->connection, $query );
        }
    }

    public function nf()
    {
        return pg_num_rows( $this->result );
    }

    public function next_record()
    {
        $this->record = pg_fetch_array( $this->result );
        return $this->record;
    }

    public function f( $field )
    {
        if ( is_array( $this->record ) && isset( $this->record[ $field ] ) )
        {
            return $this->record[ $field ];
        }
        return null;
    }

    /*
     * escape functions
     */

    public function esc( $data, $mode = self::STRING )
    {
        switch ( $mode )
        {
            case "literal":
                $data = pg_escape_literal( $this->connection, $data );
                break;
            case "bytea":
                $data = pg_escape_bytea( $this->connection, $data );
                break;
            default:
                $data = pg_escape_string( $this->connection, $data );
                break;
        }
        return $data;
    }


}


?>