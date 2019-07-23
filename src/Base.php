<?php

namespace guda\phpdfa;

class Base
{
    protected $raw_folder = '';
    protected $map_folder = '';

    public function __construct($raw_folder, $map_folder)
    {
        $this->raw_folder = $raw_folder;
        $this->map_folder = $map_folder;

        if (!is_dir($this->raw_folder) || !is_dir($this->map_folder)){
            throw new \Exception('词库目录不正确');
        }
    }
}
