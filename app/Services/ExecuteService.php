<?php

namespace App\Services;

use App\Exceptions\CustomerException;
use App\Exporter\ExcelExporter;
use App\Exporter\JsonExporter;
use App\Models\SqlExecutionLogs;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ExecuteService
{

    public function addLog(string $select_sql, string $msg, string $user)
    {
        $model = new SqlExecutionLogs();
        $model->user = $user;
        $model->created_at = date('Y-m-d H:i:s');
        $model->sql = $select_sql;
        $model->error = $msg;

        if (!$model->save()) throw new CustomerException('insert fail');
    }

    public function execute(string $select_sql, int $page): Collection
    {
        return collect(DB::select($select_sql))->forPage($page, 10);
    }

    public function export(string $select_sql)
    {
        $exporter = new ExportService('SqlLogs.xlsx');

        $exporter->setHeaders([
            'ID',
            'user',
            'sql',
            'error',
            'create-time',
        ]);

        $exporter->addRows(collect(DB::select($select_sql))->map(fn($item) => [
            'id' => $item->id,
            'user' => $item->user,
            'sql' => $item->sql,
            'error' => $item->error,
            'create_time' => $item->created_at,
        ])->toArray());

        $exporter->exportData();
    }

    public function exportJson(string $select_sql): Collection
    {
        return collect(DB::select($select_sql));
    }

    public function export2(string $select_sql, $file_name)
    {
        $collect = collect(DB::select($select_sql));

        if ('xlsx' === substr(strrchr($file_name,'.'),1)) {
            $exporter = new ExcelExporter($file_name);

            $exporter->setHeaders([
                'ID',
                'user',
                'sql',
                'error',
                'create-time',
            ]);

            $exporter->export($collect->map(fn($item) => [
                'id' => $item->id,
                'user' => $item->user,
                'sql' => $item->sql,
                'error' => $item->error,
                'create_time' => $item->created_at,
            ])->toArray());
        }

        if ('json' === substr(strrchr($file_name,'.'),1)) {
            $exporter = new JsonExporter($file_name);

            $exporter->export($collect);
        }
    }
}
