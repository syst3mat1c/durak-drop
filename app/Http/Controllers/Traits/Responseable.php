<?php

namespace App\Http\Controllers\Traits;

trait Responseable
{
    /**
     * @param array $payload
     * @param int $code
     * @return array
     */
    public function success(array $payload = [], int $code = 200)
    {
        return ['status' => $code] + (count($payload) ? compact('payload') : $payload);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function backSuccess()
    {
        return back()->with('success', 200);
    }

    /**
     * @param string $route
     * @param array $data
     * @return \Illuminate\Http\RedirectResponse
     */
    public function routeSuccess(string $route, array $data = [])
    {
        return redirect()->route($route, $data)->with('success', 200);
    }

    /**
     * @param string $url
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectSuccess(string $url = '')
    {
        return redirect($url)->with('success', 200);
    }
}
