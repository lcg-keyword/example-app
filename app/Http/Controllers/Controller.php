<?php

namespace App\Http\Controllers;

use App\Models\SqlExecutionLogs;
use App\Services\ExecuteService;
use App\Services\ExportService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct(
        private ExecuteService $service,
        private ExportService  $exportService
    )
    {
    }

    public function execute(Request $request)
    {

        $params = $request->all();

        $logs = $this->service->execute($params, $request->session()->get('username'));

        $pagers = SqlExecutionLogs::query()->paginate(10);

        return view('operate', ['logs' => $logs, 'pagers' => $pagers]);
    }

    public function exportExcel(Request $request)
    {
        $select_sql = $request->input('keyword', '');

        $result = $this->service->getExport($select_sql, $request->session()->get('username'));

        if (is_string($result)) return view('operate', ['logs' => $result]);

        $this->exportService->export($result['header'], $result['data']);

        return response('');
    }

    public function exportJson(Request $request)
    {
        $select_sql = $request->input('keyword', '');

        $result = $this->service->prepareCheck($select_sql, $request->session()->get('username'));

        if ($result) return view('operate', ['logs' => $result]);

        $jsonContent = json_encode($result, JSON_PRETTY_PRINT);

        return response()->streamDownload(function () use ($jsonContent) {
            echo $jsonContent;
        }, 'sql_logs.json', [
            'Content-Type' => 'application/json'
        ]);
    }
}
