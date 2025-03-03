<?php

namespace App\Services;

use App\Models\SqlExecutionLogs;
use Exception;
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

        if (!$model->save()) throw new Exception('insert fail');
    }

    /**
     * @throws Exception
     */
    public function execute(string $select_sql, int $page): Collection
    {
        return collect(DB::select($select_sql))->forPage($page, 10);
    }

    /**
     * @throws Exception
     */
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
}
