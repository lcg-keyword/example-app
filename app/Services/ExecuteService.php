<?php

namespace App\Services;

use App\Models\SqlExecutionLogs;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ExecuteService
{

    /**
     * @throws Exception
     */
    public function execute(array $params, string $user): Collection | string
    {
        $select_sql = Arr::get($params, 'keyword');

        if (empty($select_sql)) return '';
        if (!str_starts_with($select_sql, 'select ')) return 'invalid sql';

        try {

            DB::connection()->getPdo()->prepare($select_sql);

        }catch (Exception $exception) {

            $model = new SqlExecutionLogs();
            $model->user = $user;
            $model->created_at = date('Y-m-d H:i:s');
            $model->sql = $select_sql;
            $model->error = $exception->getMessage();

            if (!$model->save()) throw new Exception('insert fail');

            return $exception->getMessage();
        }

        return collect(DB::select($select_sql));

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
    public function getExport(array $params, string $user): array | string
    {
        $result = $this->execute($params, $user);
        if (is_string($result)) return $result;

        return [
            'header' => $this->getSheetHeader(),
            'data' => $result->map(fn($item) => [
                'id' => $item->id,
                'user' => $item->user,
                'sql' => $item->sql,
                'error' => $item->error,
                'create_time' => $item->created_at,
            ])->toArray(),
        ];
    }
}
