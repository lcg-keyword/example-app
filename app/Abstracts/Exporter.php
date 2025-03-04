<?php

namespace App\Abstracts;

abstract class Exporter
{
    protected $fileName;

    public function __construct($fileName)
    {
        $this->fileName = $fileName;
    }

    abstract public function export($data);
}
