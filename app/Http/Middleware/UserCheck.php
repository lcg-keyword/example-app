<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserCheck
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $username = $request->session()->get('username');
        $password = $request->session()->get('password');

//        if ('admin' != $username) return redirect()->route('home', ['error' => 'The page is not accessible to regular users']);
        if ('admin' != $username) return \response()->view('errors',['error' => 'The page is not accessible to regular users']);
        if ('admin' != $password) return redirect()->route('home', ['error' => 'Incorrect password']);

        return $next($request);
    }
}
