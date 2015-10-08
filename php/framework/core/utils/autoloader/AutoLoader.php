<?php

/**
 * Created by PhpStorm.
 * User: jan
 * Date: 2015.04.04.
 * Time: 11:05
 */
class AutoLoader
{
    private $classMaps;
    private $includeFile;
    private $slash;
    const WIN_SLASH = '\\';
    const LINUX_SLASH = '/';
    const CLASS_MAPPING_FILE = 'autoloader.classes.include.php';

    public function __construct()
    {
        $this->setOSDependentSlash();
        $this->includeFile = __DIR__ . $this->slash . self::CLASS_MAPPING_FILE;
        $this->getStoredClassMapping();
        spl_autoload_register( array( $this, 'loader' ) );
    }

    private function getStoredClassMapping()
    {
        $this->classMaps = [ ];

        include_once $this->includeFile;
    }

    private function loader( $className )
    {
        $file_to_include = $this->getFilePath( $className );
        if ( file_exists( $file_to_include ) )
        {
            include_once $file_to_include;
        }
    }

    private function getFilePath( $className )
    {
        if ( !isset( $this->classMaps[ $className ] ) )
        {
            $classPath = $this->searchForClassPath( $className );
            $this->saveClassMapping( $className, $classPath );
        } else
        {
            $classPath = $this->classMaps[ $className ];
        }

        return  $classPath ;
    }

    private function searchForClassPath( $className )
    {
        $recursiveDirectoryIterator = new RecursiveDirectoryIterator( realpath( '' ) );
        $recursiveIterator = new RecursiveIteratorIterator( $recursiveDirectoryIterator, RecursiveIteratorIterator::SELF_FIRST );
        $regex = '/' . $className . '.php'. '/';
        $regexIterator = new RegexIterator( $recursiveIterator, $regex );

        foreach ( $regexIterator as $value )
        {
            if ( strpos( $value, $this->slash . $className . '.php' ) )
            {
                break;
            }
        }

        return $value;
    }

    private function saveClassMapping( $className, $classPath )
    {
        $newLine = PHP_EOL . '    $this->classMaps["' . $className . '"] = ' . "'" . $classPath . "';";
        file_put_contents( $this->includeFile, $newLine, FILE_APPEND );
    }

    private function setOSDependentSlash()
    {
        if ( PHP_OS == 'WINNT' )
        {
            $this->slash = self::WIN_SLASH;
        } else
        {
            $this->slash = self::LINUX_SLASH;
        }
    }
}