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

//    public function prepareCheck(string $select_sql, string $user): string
//    {
//        if (!str_starts_with($select_sql, 'select ')) return 'Only SELECT is allowed';
//
//        try {
//
//            DB::connection()->getPdo()->prepare($select_sql);
//
//        } catch (Exception $exception) {
//
//            $model = new SqlExecutionLogs();
//            $model->user = $user;
//            $model->created_at = date('Y-m-d H:i:s');
//            $model->sql = $select_sql;
//            $model->error = $exception->getMessage();
//
//            if (!$model->save()) throw new Exception('insert fail');
//
//            return $exception->getMessage();
//        }
//
//        return '';
//    }

    /**
     * @throws Exception
     */
    public function execute(string $select_sql, int $page): Collection
    {
        return collect(DB::select($select_sql))->forPage($page, 10);
    }

    function getSheetHeader(): array
    {
        $header = [
            'ID',
            'user',
            'sql',
            'error',
            'create-time',
        ];
        $data = [];
        array_reduce($header, function ($pre_define, $item) use (&$data) {
            $data[$pre_define . '1'] = $item;
            return ++$pre_define;
        }, 'A');

        return $data;
    }

    /**
     * @throws Exception
     */
    public function getExport(string $select_sql): array
    {
        return [
            $this->getSheetHeader(),
            collect(DB::select($select_sql))->map(fn($item) => [
                'id' => $item->id,
                'user' => $item->user,
                'sql' => $item->sql,
                'error' => $item->error,
                'create_time' => $item->created_at,
            ])->toArray(),
        ];
    }

    public function exportJson(string $select_sql): Collection
    {
        return collect(DB::select($select_sql));
    }
}
