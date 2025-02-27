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

        try {
            $data = DB::select($select_sql);
        }catch (QueryException $exception) {

            $model = new SqlExecutionLogs();
            $model->user = $user;
            $model->created_at = date('Y-m-d H:i:s');
            $model->sql = $select_sql;
            $model->error = $exception->getMessage();

            if (!$model->save()) throw new Exception('insert fail');

            return $exception->getMessage();
        }

        return new Collection($data ?? []);

    }

}
