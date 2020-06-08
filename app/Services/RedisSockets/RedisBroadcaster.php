<?php

namespace App\Services\RedisSockets;

use Redis;

class RedisBroadcaster
{
    /**
     * @param string $channel
     * @param array $data
     * @return int
     */
    public function publish(string $channel, array $data)
    {
        return Redis::publish($channel, json_encode($data, 256));
    }
}
