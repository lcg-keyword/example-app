<?php

namespace App\Services;

use App\Models\SqlExecutionLogs;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ExecuteService
{

    public function prepareCheck(string $select_sql, string $user): string
    {
        if (!str_starts_with($select_sql, 'select ')) return 'invalid sql';

        try {

            DB::connection()->getPdo()->prepare($select_sql);

        } catch (Exception $exception) {

            $model = new SqlExecutionLogs();
            $model->user = $user;
            $model->created_at = date('Y-m-d H:i:s');
            $model->sql = $select_sql;
            $model->error = $exception->getMessage();

            if (!$model->save()) throw new Exception('insert fail');

            return $exception->getMessage();
        }

        return '';
    }

    /**
     * @throws Exception
     */
    public function execute(string $select_sql, int $page, string $user): Collection|string
    {
        if ($msg = $this->prepareCheck($select_sql, $user)) return $msg;

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
    public function getExport(string $select_sql, string $user): array|string
    {
        if ($msg = $this->prepareCheck($select_sql, $user)) return $msg;

        return [
            'header' => $this->getSheetHeader(),
            'data' => collect(DB::select($select_sql))->map(fn($item) => [
                'id' => $item->id,
                'user' => $item->user,
                'sql' => $item->sql,
                'error' => $item->error,
                'create_time' => $item->created_at,
            ])->toArray(),
        ];
    }

    public function exportJson(string $select_sql, string $user): Collection|string
    {
        if ($msg = $this->prepareCheck($select_sql, $user)) return $msg;

        return collect(DB::select($select_sql));
    }
}
