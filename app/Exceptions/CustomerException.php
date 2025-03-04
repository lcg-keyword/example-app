<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CustomerException extends Exception
{

    public function render(Request $request): Response
    {
        return \response()->view('errors',['error' => $this->getMessage()],500);
    }

}
