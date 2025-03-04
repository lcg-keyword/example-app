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
        private SqlValidator   $sqlValidator
    )
    {
    }

    public function execute(Request $request)
    {

        $params = $request->all();

        if (empty($params['page'])) $msg = $this->sqlValidator->validateSelectSql($params['keyword'] ?? '');

        if (empty($params['page'])) $this->service->addLog($params['keyword'] ?? '', $msg, $request->session()->get('username'));

        if (!($msg ?? '')) $logs = $this->service->execute($params['keyword'] ?? '', $params['page'] ?? 1);

        return view('operate', [
            'logs' => $logs ?? ($msg ?? ''),
            'pagers' => SqlExecutionLogs::query()->paginate(10),
            'keyword' => $params['keyword'] ?? ''
        ]);
    }

    public function exportExcel(Request $request)
    {
        $params = $request->all();

        $msg = $this->sqlValidator->validateSelectSql($params['keyword'] ?? '');

        $this->service->addLog($params['keyword'] ?? '', $msg, $request->session()->get('username'));

        if ($msg) return view('operate', ['logs' => $msg]);

        $this->service->export($params['keyword'] ?? '', 'SqlLogs.xlsx');

        return response('');
    }

    public function exportJson(Request $request)
    {
        $params = $request->all();

        $msg = $this->sqlValidator->validateSelectSql($params['keyword'] ?? '');

        $this->service->addLog($params['keyword'] ?? '', $msg, $request->session()->get('username'));

        if ($msg) return view('operate', ['logs' => $msg]);

        $this->service->export($params['keyword'] ?? '', 'SqlLogs.json');

        return response('');
    }
}
