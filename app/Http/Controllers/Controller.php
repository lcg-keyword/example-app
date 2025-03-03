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

        $logs = $this->service->execute($params['keyword'] ?? '', $params['page'] ?? 1, $request->session()->get('username'));

        $pagers = SqlExecutionLogs::query()->paginate(10);

        return view('operate', ['logs' => $logs, 'pagers' => $pagers, 'keyword' => $params['keyword'] ?? '']);
    }

    public function exportExcel(Request $request)
    {
        $params = $request->all();

        $result = $this->service->getExport($params['keyword'] ?? '', $request->session()->get('username'));

        if (is_string($result)) return view('operate', ['logs' => $result]);

        $this->exportService->export($result['header'], $result['data']);

        return response('');
    }

    public function exportJson(Request $request)
    {
        $params = $request->all();

        $result = $this->service->exportJson($params['keyword'] ?? '', $request->session()->get('username'));

        if (is_string($result)) return view('operate', ['logs' => $result]);

        return response()->streamDownload(function () use ($result) {
            echo json_encode($result, JSON_PRETTY_PRINT);
        }, 'sql_logs.json', [
            'Content-Type' => 'application/json'
        ]);
    }
}
