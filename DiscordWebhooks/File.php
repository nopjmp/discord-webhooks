<?php

namespace DiscordWebhooks;

class File {

    private $file;
    private $name;

    public function __construct($path, $name = null) {
        $this->file = realpath($path);
        if ($name == null) {
            $this->name = basename($path);
        } else  {
            $this->name = $name;
        }
    }
    public function getFile() {
        return $this->file;
    }
    public function getName() {
        return $this->name;
    }
}
