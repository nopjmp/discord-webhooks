<?php

namespace DiscordWebhooks\Tests;

use DiscordWebhooks\Client;
use PHPUnit\Framework\TestCase;

class ClientTransportTest extends TestCase
{
    /**
     * @requires extension curl
     */
    public function testSendTimesOutWhenServerDoesNotRespond(): void
    {
        $server = stream_socket_server('tcp://127.0.0.1:0');
        $this->assertIsResource($server);
        $address = stream_socket_get_name($server, false);

        try {
            $client = new Client('http://' . $address, 1, 1);
            $this->expectException(\Exception::class);
            $this->expectExceptionMessage('cURL error (28)');
            $client->message('Hello')->send();
        } finally {
            fclose($server);
        }
    }
}
