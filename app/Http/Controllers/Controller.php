<?php

namespace App\Http\Controllers;

use App\Models\SqlExecutionLogs;
use App\Services\ExecuteService;
use App\Services\ExportService;
use App\Validators\SqlValidator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct(
        private ExecuteService $service,
        private ExportService  $exportService,
        private SqlValidator $sqlValidator
    )
    {
    }

    public function execute(Request $request)
    {

        $params = $request->all();

        if ($msg = $this->sqlValidator->validateSelectSql($params['keyword'] ?? '')) {
            $this->service->addLog($params['keyword'] ?? '', $msg, $request->session()->get('username'));
        }else {
            $logs = $this->service->execute($params['keyword'] ?? '', $params['page'] ?? 1);
        }

        return view('operate', [
            'logs' => $logs ?? $msg,
            'pagers' => SqlExecutionLogs::query()->paginate(10),
            'keyword' => $params['keyword'] ?? ''
        ]);
    }

    public function exportExcel(Request $request)
    {
        $params = $request->all();

        if ($msg = $this->sqlValidator->validateSelectSql($params['keyword'] ?? '')) {
            $this->service->addLog($params['keyword'] ?? '', $msg, $request->session()->get('username'));
            return view('operate', ['logs' => $msg]);
        }

        [$header, $data] = $this->service->getExport($params['keyword'] ?? '');

        $this->exportService->export($header, $data);

        return response('');
    }

    public function exportJson(Request $request)
    {
        $params = $request->all();

        if ($msg = $this->sqlValidator->validateSelectSql($params['keyword'] ?? '')) {
            $this->service->addLog($params['keyword'] ?? '', $msg, $request->session()->get('username'));
            return view('operate', ['logs' => $msg]);
        }

        $result = $this->service->exportJson($params['keyword'] ?? '');

        return response()->streamDownload(function () use ($result) {
            echo json_encode($result, JSON_PRETTY_PRINT);
        }, 'sql_logs.json', [
            'Content-Type' => 'application/json'
        ]);
    }
}
