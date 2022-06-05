<?php
namespace ECD\Core;

/**
 * Common code for working with file system files.
 */
trait FileHelpers
{
    /**
     * Checks that the file exists and is readable.  This includes checking it is
     * a file as opposed to something else (directory etc.)
     * @param string $fileName
     * @throws \Exception
     */
    function checkReadable ( string $fileName )
    {
        // Check if exists and is not readable (note this will return true for directories)
        if ( is_readable($fileName) === false )	{
            throw new \Exception("Input file {$fileName} not found/readable.");
        }
        // Check if it's not a file, i.e. directory etc.
        if ( is_file($fileName) === false )	{
            throw new \Exception("Input file {$fileName} not a file.");
        }
    }

    /**
     * Checks if the file exists, it is writeable. If it doesn't exist, it checks
     * that the directory is writeable.
     * @param string $fileName
     * @throws \Exception
     */
    function checkWritable ( string $fileName )
    {
        if ( file_exists($fileName) === true )  {
            // Check if exists and is not writable.
            if ( is_writable($fileName) === false )	{
                throw new \Exception("File {$fileName} not writable.");
            }
        }
        else    {
            // Check if directory is writable
            if ( is_writable(dirname($fileName)) === false )	{
                throw new \Exception("File {$fileName} not writable.");
            }
        }
    }
}