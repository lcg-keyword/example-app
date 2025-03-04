<?php

namespace App\Exporter;

use App\Abstracts\Exporter;

class JsonExporter extends Exporter
{

    public function __construct($fileName)
    {
        parent::__construct($fileName);
    }

    public function export($data)
    {
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);

        header('Content-Type: application/json');
        header('Content-Disposition: attachment;filename="'.$this->fileName);

        echo $jsonData;
    }
}
