<?php

namespace App\Http\Controllers;

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
        private ExportService $exportService
    )
    {
    }

    public function execute(Request $request)
    {

        $logs = $this->service->execute($request->all(), $request->session()->get('username'));

        return view('operate',['logs' => $logs]);
    }

    public function exportExcel(Request $request)
    {
        $result = $this->service->getExport($request->all(), $request->session()->get('username'));

        if (is_string($result)) return view('operate',['logs' => $result]);

        $this->exportService->export($result['header'], $result['data']);

        return response('');
    }

    public function exportJson(Request $request)
    {
        $result = $this->service->execute($request->all(), $request->session()->get('username'));

        if (is_string($result)) return view('operate',['logs' => $result]);

        $jsonContent = json_encode($result, JSON_PRETTY_PRINT);

        return response()->streamDownload(function () use ($jsonContent) {
            echo $jsonContent;
        }, 'sql_logs.json', [
            'Content-Type' => 'application/json'
        ]);
    }
}
