<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ExportService
{

    public function export($header, $data, $type = true, $filename = null)
    {

        // 创建一个新的 Spreadsheet 对象
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // 设置表头
        foreach ($header as $k => $v) {
            $sheet->setCellValue($k, $v);
        }

        // 填充数据到表格
        $sheet->fromArray($data, null, "A2");

        // 样式设置
        $styleArray = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ]
        ];
        $sheet->getStyle('A:Z')->applyFromArray($styleArray);
        $sheet->getDefaultColumnDimension()->setWidth(30);

        // 设置下载与后缀
        if ($type) {
            $type = "Xlsx";
        } else {
            $type = "Xlsx";
        }

        // 设置响应头
        ob_end_clean();//清除缓存区
        header('pragma:public');
        header("Content-type:application/vnd.ms-excel;charset=utf-8");
        header("Content-Disposition:attachment");

        // 调用方法执行下载
        $writer = IOFactory::createWriter($spreadsheet, $type);
        // 数据流
        $writer->save("php://output");

    }

}
