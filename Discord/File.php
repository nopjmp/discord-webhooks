<?php

namespace Discord;

/**
 * Class File
 *
 * Represents a file that can be passed
 * to be downloaded.
 *
 * @author  Scrummer <scrummer@labymod.net>
 * @package Discord
 */
class File
{
    /**
     * @var string
     */
    private $file;

    /**
     * @var string
     */
    private $fileName;

    /**
     * Constructor.
     *
     * @param string $relativPath
     * @param string $name
     */
    public function __construct( $relativPath, $name = null )
    {
        $this->file = realpath( $relativPath );
        $this->fileName = null === $name ? basename( $$relativPath ) : $name;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }
}