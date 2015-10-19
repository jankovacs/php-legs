<?php

interface IMapper {
    function map( $from, $to );
    function unmap( $from, $to );
    function getMappings();
}