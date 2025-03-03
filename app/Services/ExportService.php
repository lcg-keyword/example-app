<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportService
{

    private $spreadsheet;
    private $sheet;
    private $fileName;

    public function __construct($fileName = 'export.xlsx')
    {
        $this->spreadsheet = new Spreadsheet();
        $this->sheet = $this->spreadsheet->getActiveSheet();
        $this->fileName = $fileName;
    }

    public function setHeaders($headers)
    {
        $column = 'A';
        foreach ($headers as $header) {
            $this->sheet->setCellValue($column . '1', $header);
            $column++;
        }
        return $this;
    }

    public function addRows($rows)
    {
        $row = 2;
        foreach ($rows as $dataRow) {
            $column = 'A';
            foreach ($dataRow as $cellValue) {
                $this->sheet->setCellValue($column . $row, $cellValue);
                $column++;
            }
            $row++;
        }
        return $this;
    }

    public function exportData()
    {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $this->fileName . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($this->spreadsheet);
        $writer->save('php://output');
    }

}
