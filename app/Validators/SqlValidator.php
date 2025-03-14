<?php

namespace App\Validators;

use Illuminate\Support\Facades\DB;

class SqlValidator
{

    public function validateSelectSql(string $select_sql): string
    {

        if (!str_starts_with($select_sql, 'select ')) return 'Only SELECT is allowed';

        try {

            DB::connection()->getPdo()->prepare($select_sql);

        } catch (\Exception $exception) {

            return $exception->getMessage();
        }

        return '';
    }

}
