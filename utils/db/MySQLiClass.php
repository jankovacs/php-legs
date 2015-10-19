<?php

class MySQLiClass
{
    public $record;
    private $db;
    private $result;

    public function __construct( $host, $user, $pwd, $dbname )
    {

        $this->db = new mysqli( $host, $user, $pwd, $dbname );

        if ( mysqli_connect_errno() )
        {
            throw new Exception( "Can't connect to mysql..." . mysqli_connect_errno() . ': ' . mysqli_error() );
        }
    }

    public function isConnected()
    {
        return $this->db->get_connection_stats();
    }

    public function autoExec( $tablename, $tabledata, $mode, $where = null )
    {
        $fields = array();
        foreach ( $tabledata as $key => $value )
        {
            $fields[ ] = $key . '="' . $value . '"';
        }

        return $this->query( strtoupper( $mode ) . ' ' . $tablename . ' SET ' . implode( ',', $fields ) . ( $where != null ? ' WHERE ' . $where : null ) );
    }

    public function query( $query_string, $statements = null )
    {

        if ( $statements == null )
        {

            $this->result = $this->db->query( $query_string );

        } else
        {

            /* Create a prepared statement */
            if ( $stmt = $this->db->prepare( $query_string ) )
            {

                /* Bind parameters
                   s - string, b - boolean, i - int, etc */

                $type = $this->getStatementType( $statements );
                call_user_func_array( 'mysqli_stmt_bind_param', array_merge( array( $sql_stmt, $type ), $statements ) );

                /* Execute it */
                $stmt->execute();

                /* Bind results */
                $this->result->bind_result( $result );

                /* Close statement */
                $stmt->close();
            } else
            {
                return false;
            }
        }

        if ( is_object( $this->result ) && $this->result->num_rows > 0 )
        {
            return true;
        } else
        {
            return false;
        }

    }

    private function getStatementType( $array )
    {
        $states = '';

        foreach ( $array as $key => $value )
        {
            if ( is_string( $value ) )
            {
                $states .= 's';
            } else
            {
                if ( is_int( $value ) )
                {
                    $states .= 'i';
                } else
                {
                    if ( is_double( $value ) )
                    {
                        $states .= 'd';
                    } else
                    {
                        if ( is_bool( $value ) )
                        {
                            $states .= 's';
                        }
                    }
                }
            }
        }

        return $states;
    }

    public function next_record()
    {
        $this->record = $this->result->fetch_assoc();
        if ( is_array( $this->record ) )
        {
            return true;
        } else
        {
            return false;
        }
    }

    public function nf()
    {
        if ( is_object( $this->result ) )
        {
            return $this->result->num_rows;
        } else
        {
            return 0;
        }
    }

    public function af()
    {
        return mysqli_affected_rows( $this->db );
    }

    public function insertid()
    {
        $this->query( 'SELECT LAST_INSERT_ID() insertid' );
        $this->next_record();
        return $this->f( 'insertid' );
    }

    public function f( $fieldname )
    {
        if ( isset( $this->record[ $fieldname ] ) )
        {
            return $this->record[ $fieldname ];
        } else
        {
            return null;
        }
    }

    /* helper functions */

    public function record()
    {
        return $this->record;
    }

    public function getError()
    {
        return @mysqli_error( $this->db );
    }

    public function getErrorNo()
    {
        return @mysqli_errno( $this->db );
    }

    public function esc( $string, $wrap = true )
    {
        $string = html_entity_decode( $string );
        $string = htmlspecialchars_decode( $string );

        return mysqli_real_escape_string( $this->db, $string );
    }

    public function escape( $s, $wrap = true )
    {
        $wrap = ( $wrap === true ? '\'' : '' );

        return ( $s === null ? ( $wrap ? '""' : 0 ) : $wrap . str_replace( array( '\\', "'" ), array( '\\\\', "\\'" ), $s ) . $wrap );
    }

}

?>
