<?php

namespace App\Abstracts;

abstract class Exporter
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    abstract public function export($filename);
}
