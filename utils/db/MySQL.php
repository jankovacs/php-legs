<?php

/**
 * @deprecated
 */
class MySQL
{
    /* public: connection parameters */
    public $Host = "";
    public $Database = "";
    public $User = "";
    public $Password = "";

    /* public: configuration parameters */
    public $Auto_Free = 0;     ## Set to 1 for automatic mysql_free_result()
    public $Debug = 0;     ## Set to 1 for debugging messages.
    public $Halt_On_Error = "report"; ## "yes" (halt with message), "no" (ignore errors quietly), "report" (ignore errror, but spit a warning)
    public $PConnect = 0;     ## Set to 1 to use persistent database connections
    public $Seq_Table = "db_sequence";

    /* public: result array and current row number */
    public $record = array();
    public $Row;

    /* public: current error number and error text */
    public $Errno = 0;
    public $Error = "";

    /* public: this is an api revision, not a CVS revision. */
    public $type = "mysqli";
    public $revision = "1.2";

    /* private: link and query handles */
    public $Link_ID = 0;
    public $Query_ID = 0;

    public $locked = false;      ## set to true while we have a lock

    /* public: constructor */
    public function DB_Sql( $query = "" )
    {
        $this->query( $query );
    }

    /* public: some trivial reporting */
    /**
     * @deprecated
     */
    function query( $Query_String )
    {
        /* No empty queries, please, since PHP4 chokes on them. */
        if ( $Query_String == "" )
            /* The empty query string is passed on from the constructor,
             * when calling the class without a query, e.g. in situations
             * like these: '$db = new DB_Sql_Subclass;'
             */
            return 0;

        if ( !$this->connect() )
        {
            return 0; /* we already complained in connect() about that. */
        };

        # New query, discard previous result.
        if ( $this->Query_ID )
        {
            $this->free();
        }

        if ( $this->Debug )
            printf( "Debug: query = %s<br>\n", $Query_String );

        $this->Query_ID = @mysql_query( $Query_String, $this->Link_ID );
        $this->Row = 0;
        $this->Errno = mysql_errno();
        $this->Error = mysql_error();
        if ( !$this->Query_ID )
        {
            $this->halt( "Invalid SQL: " . $Query_String );
        }

        # Will return nada if it fails. That's fine.
        return $this->Query_ID;
    }

    /**
     * @deprecated
     */
    public function connect( $Database = "", $Host = "", $User = "", $Password = "" )
    {
        /* Handle defaults */
        if ( "" == $Database )
            $Database = $this->Database;
        if ( "" == $Host )
            $Host = $this->Host;
        if ( "" == $User )
            $User = $this->User;
        if ( "" == $Password )
            $Password = $this->Password;

        /* establish connection, select database */
        if ( 0 == $this->Link_ID )
        {

            if ( !$this->PConnect )
            {
                $this->Link_ID = mysql_connect( $Host, $User, $Password );
            } else
            {
                $this->Link_ID = mysql_pconnect( $Host, $User, $Password );
            }
            if ( !$this->Link_ID )
            {
                $this->halt( "connect($Host, $User, \$Password) failed." );
                return 0;
            }

            if ( !@mysql_select_db( $Database, $this->Link_ID ) )
            {
                $this->halt( "cannot use database " . $Database );
                return 0;
            }
        }

        return $this->Link_ID;
    }

    /**
     * @deprecated
     */
    public function isConnected()
    {
        return $this->Link_ID;
    }

    /* public: connection management */
    /**
     * @deprecated
     */
    function halt( $msg )
    {
        $this->Error = @mysql_error( $this->Link_ID );
        $this->Errno = @mysql_errno( $this->Link_ID );

        if ( $this->locked )
        {
            $this->unlock();
        }

        if ( $this->Halt_On_Error == "no" )
            return;

        $this->haltmsg( $msg );

        if ( $this->Halt_On_Error != "report" )
            die( "Session halted." );
    }

    /**
     * @deprecated
     */
    function unlock()
    {

        // set before unlock to avoid potential loop
        $this->locked = false;

        if ( !$this->query( "unlock tables" ) )
        {
            $this->halt( "unlock() failed." );
            return false;
        }
        return true;
    }

    /* public: discard the query result */
    /**
     * @deprecated
     */
    function haltmsg( $msg )
    {
        printf( "</td></tr></table><b>Database error:</b> %s<br>\n", $msg );
        printf( "<b>MySQL Error</b>: %s (%s)<br>\n",
            $this->Errno,
            $this->Error );
    }

    /* public: perform a query */
    /**
     * @deprecated
     */
    function free()
    {
        @mysql_free_result( $this->Query_ID );
        $this->Query_ID = 0;
    }

    /**
     * @deprecated
     */
    public function query_id()
    {
        return $this->Query_ID;
    }

    /* public: walk result set */
    /**
     * @deprecated
     */
    function disconnect()
    {
        @mysql_close( $this->link_id() );
    }

    /* public: position in result set */
    /**
     * @deprecated
     */
    public function link_id()
    {
        return $this->Link_ID;
    }

    /* public: table locking */
    /**
     * @deprecated
     */
    public function autoExec( $tablename, $tabledata, $mode, $where = null )
    {
        $fields = array();
        foreach ( $tabledata as $key => $value )
        {
            $fields[ ] = $key . '="' . $value . '"';
        }
        return $this->query( strtoupper( $mode ) . ' ' . $tablename . ' SET ' . implode( ',', $fields ) . ( $where != null ? ' WHERE ' . $where : null ) );
    }

    /**
     * @deprecated
     */
    function seek( $pos = 0 )
    {
        $status = @mysql_data_seek( $this->Query_ID, $pos );
        if ( $status )
            $this->Row = $pos;
        else
        {
            $this->halt( "seek($pos) failed: result has " . $this->num_rows() . " rows." );

            /* half assed attempt to save the day,
             * but do not consider this documented or even
             * desireable behaviour.
             */
            @mysql_data_seek( $this->Query_ID, $this->num_rows() );
            $this->Row = $this->num_rows();
            return 0;
        }

        return 1;
    }

    /* public: evaluate the result (size, width) */
    /**
     * @deprecated
     */
    public function num_rows()
    {
        return @mysql_num_rows( $this->Query_ID );
    }

    /**
     * @deprecated
     */
    public function affected_rows()
    {
        $this->query( 'SELECT ROW_COUNT() AS affected_rows' );
        if ( $this->nf() && $this->next_record() )
        {
            return intval( $db->f( 'affected_rows' ) );
        } else
        {
            return 0;
        }
        //return @mysql_affected_rows($this->Link_ID);
    }

    /**
     * @deprecated
     */
    function nf()
    {
        return $this->num_rows();
    }

    /**
     * @deprecated
     */
    function next_record()
    {
        if ( !$this->Query_ID )
        {
            $this->halt( "next_record called with no query pending." );
            return 0;
        }

        $this->record = @mysql_fetch_array( $this->Query_ID );
        $this->Row += 1;
        $this->Errno = mysql_errno();
        $this->Error = mysql_error();

        $stat = is_array( $this->record );
        if ( !$stat && $this->Auto_Free )
        {
            $this->free();
        }
        return $stat;
    }

    /* public: shorthand notation */
    /**
     * @deprecated
     */
    public function num_fields()
    {
        return @mysql_num_fields( $this->Query_ID );
    }

    /**
     * @deprecated
     */
    public function insert_id()
    {
        $this->query( "SELECT LAST_INSERT_ID() AS lastid" );
        if ( $this->nf() && $this->next_record() )
        {
            return $this->f( "lastid" );
        } else
        {
            return 0;
        }
    }

    /**
     * @deprecated
     */
    function f( $Name )
    {
        if ( isset( $this->record[ $Name ] ) )
        {
            return $this->record[ $Name ];
        }
    }

    /**
     * @deprecated
     */
    function np()
    {
        print $this->num_rows();
    }

    /* public: sequence numbers */
    /**
     * @deprecated
     */
    function p( $Name )
    {
        if ( isset( $this->record[ $Name ] ) )
        {
            print $this->record[ $Name ];
        }
    }

    /* public: return table metadata */
    /**
     * @deprecated
     */
    function nextid( $seq_name )
    {
        /* if no current lock, lock sequence table */
        if ( !$this->locked )
        {
            if ( $this->lock( $this->Seq_Table ) )
            {
                $locked = true;
            } else
            {
                $this->halt( "cannot lock " . $this->Seq_Table . " - has it been created?" );
                return 0;
            }
        }

        /* get sequence number and increment */
        $q = sprintf( "select nextid from %s where seq_name = '%s'",
            $this->Seq_Table,
            $seq_name );
        if ( !$this->query( $q ) )
        {
            $this->halt( 'query failed in nextid: ' . $q );
            return 0;
        }

        /* No current value, make one */
        if ( !$this->next_record() )
        {
            $currentid = 0;
            $q = sprintf( "insert into %s values('%s', %s)",
                $this->Seq_Table,
                $seq_name,
                $currentid );
            if ( !$this->query( $q ) )
            {
                $this->halt( 'query failed in nextid: ' . $q );
                return 0;
            }
        } else
        {
            $currentid = $this->f( "nextid" );
        }
        $nextid = $currentid + 1;
        $q = sprintf( "update %s set nextid = '%s' where seq_name = '%s'",
            $this->Seq_Table,
            $nextid,
            $seq_name );
        if ( !$this->query( $q ) )
        {
            $this->halt( 'query failed in nextid: ' . $q );
            return 0;
        }

        /* if nextid() locked the sequence table, unlock it */
        if ( $locked )
        {
            $this->unlock();
        }

        return $nextid;
    }

    /* public: find available table names */
    /**
     * @deprecated
     */
    function lock( $table, $mode = "write" )
    {
        $query = "lock tables ";
        if ( is_array( $table ) )
        {
            while ( list( $key, $value ) = each( $table ) )
            {
                // text keys are "read", "read local", "write", "low priority write"
                if ( is_int( $key ) ) $key = $mode;
                if ( strpos( $value, "," ) )
                {
                    $query .= str_replace( ",", " $key, ", $value ) . " $key, ";
                } else
                {
                    $query .= "$value $key, ";
                }
            }
            $query = substr( $query, 0, -2 );
        } elseif ( strpos( $table, "," ) )
        {
            $query .= str_replace( ",", " $mode, ", $table ) . " $mode";
        } else
        {
            $query .= "$table $mode";
        }
        if ( !$this->query( $query ) )
        {
            $this->halt( "lock() failed." );
            return false;
        }
        $this->locked = true;
        return true;
    }

    /* private: error handling */
    /**
     * @deprecated
     */
    function metadata( $table = "", $full = false )
    {
        $count = 0;
        $id = 0;
        $res = array();

        /*
         * Due to compatibility problems with Table we changed the behavior
         * of metadata();
         * depending on $full, metadata returns the following values:
         *
         * - full is false (default):
         * $result[]:
         *   [0]["table"]  table name
         *   [0]["name"]   field name
         *   [0]["type"]   field type
         *   [0]["len"]    field length
         *   [0]["flags"]  field flags
         *
         * - full is true
         * $result[]:
         *   ["num_fields"] number of metadata records
         *   [0]["table"]  table name
         *   [0]["name"]   field name
         *   [0]["type"]   field type
         *   [0]["len"]    field length
         *   [0]["flags"]  field flags
         *   ["meta"][field name]  index of field named "field name"
         *   This last one could be used if you have a field name, but no index.
         *   Test:  if (isset($result['meta']['myfield'])) { ...
         */

        // if no $table specified, assume that we are working with a query
        // result
        if ( $table )
        {
            $this->connect();
            $id = @mysql_list_fields( $this->Database, $table );
            if ( !$id )
            {
                $this->halt( "Metadata query failed." );
                return false;
            }
        } else
        {
            $id = $this->Query_ID;
            if ( !$id )
            {
                $this->halt( "No query specified." );
                return false;
            }
        }

        $count = @mysql_num_fields( $id );

        // made this IF due to performance (one if is faster than $count if's)
        if ( !$full )
        {
            for ( $i = 0; $i < $count; $i++ )
            {
                $res[ $i ][ "table" ] = @mysql_field_table( $id, $i );
                $res[ $i ][ "name" ] = @mysql_field_name( $id, $i );
                $res[ $i ][ "type" ] = @mysql_field_type( $id, $i );
                $res[ $i ][ "len" ] = @mysql_field_len( $id, $i );
                $res[ $i ][ "flags" ] = @mysql_field_flags( $id, $i );
            }
        } else
        { // full
            $res[ "num_fields" ] = $count;

            for ( $i = 0; $i < $count; $i++ )
            {
                $res[ $i ][ "table" ] = @mysql_field_table( $id, $i );
                $res[ $i ][ "name" ] = @mysql_field_name( $id, $i );
                $res[ $i ][ "type" ] = @mysql_field_type( $id, $i );
                $res[ $i ][ "len" ] = @mysql_field_len( $id, $i );
                $res[ $i ][ "flags" ] = @mysql_field_flags( $id, $i );
                $res[ "meta" ][ $res[ $i ][ "name" ] ] = $i;
            }
        }

        // free the result only if we were called on a table
        if ( $table )
        {
            @mysql_free_result( $id );
        }
        return $res;
    }

    /**
     * @deprecated
     */
    function table_names()
    {
        $this->connect();
        $h = @mysql_query( "show tables", $this->Link_ID );
        $i = 0;
        while ( $info = @mysql_fetch_row( $h ) )
        {
            $return[ $i ][ "table_name" ] = $info[ 0 ];
            $return[ $i ][ "tablespace_name" ] = $this->Database;
            $return[ $i ][ "database" ] = $this->Database;
            $i++;
        }

        @mysql_free_result( $h );
        return $return;
    }

    /**
     * @deprecated
     */
    function getError()
    {
        return @mysql_error( $this->Link_ID );
    }

    /**
     * @deprecated
     */
    function getErrorNo()
    {
        return @mysql_errno( $this->Link_ID );
    }

    /**
     * @deprecated
     */
    function esc( $string, $wrap = true )
    {
        return $this->escape( $string, $wrap );
    }

    /**
     * @deprecated
     */
    function escape( $s, $wrap = true )
    {
        $wrap = ( $wrap === true ? '\'' : '' );
        return ( $s === null ? ( $wrap ? '""' : 0 ) : $wrap . str_replace( array( '\\', "'" ), array( '\\\\', "\\'" ), $s ) . $wrap );
    }


}

?>