<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Response;

trait ApiResponseable
{
    /**
     * @param string $message
     * @param int $code
     * @param array $payload
     * @param int $status
     * @param array $headers
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function error(string $message, int $code = 500, array $payload = [], int $status = 500, array $headers = [])
    {
        return response(compact('message', 'payload', 'code'), $status, $headers);
    }

    /**
     * @param string $message
     * @param int $code
     * @param array $payload
     * @param array $headers
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function success(int $code = 200, array $payload = [], string $message = 'Успешное выполнение', array $headers = [])
    {
        return response(compact('message', 'code', 'payload'), 200, $headers);
    }
}
