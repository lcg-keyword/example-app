<?php

namespace App\Http\Controllers;

use App\Services\ExecuteService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct(private ExecuteService $service)
    {
    }

    public function execute(Request $request)
    {

//        return response()->json($this->service->execute($request->all(), $request->session()->get('username')));
        $logs = $this->service->execute($request->all(), $request->session()->get('username'));

        return view('operate',['logs' => $logs]);
    }
}
