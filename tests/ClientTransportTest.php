<?php

namespace DiscordWebhooks\Tests;

use DiscordWebhooks\Client;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use PHPUnit\Framework\TestCase;

class ClientTransportTest extends TestCase
{
    #[RequiresPhpExtension('curl')]
    #[DataProvider('requestTypeProvider')]
    public function testSendIncludesUserAgentAndTimesOutWithoutResponse(bool $withFiles, ?string $userAgent): void
    {
        $server = stream_socket_server('tcp://127.0.0.1:0');
        $this->assertIsResource($server);
        $address = stream_socket_get_name($server, false);

        try {
            $client = new Client('http://' . $address, 1, 1);
            if ($userAgent !== null) {
                $this->assertSame($client, $client->userAgent($userAgent));
            }
            if ($withFiles) {
                $client->addStringFile('file content', 'test.txt');
            }
            $this->expectException(\Exception::class);
            $this->expectExceptionMessage('cURL error (28)');
            $client->message('Hello')->send();
        } finally {
            try {
                $connection = stream_socket_accept($server, 1);
                $this->assertIsResource($connection);
                try {
                    stream_set_timeout($connection, 1);
                    $headers = '';
                    while (($line = fgets($connection)) !== false && $line !== "\r\n") {
                        $headers .= $line;
                    }
                    $expectedUserAgent = $userAgent ?? 'DiscordBot (https://github.com/nopjmp/discord-webhooks, ' . Client::VERSION . ')';
                    $this->assertStringContainsString(
                        "\r\nUser-Agent: " . $expectedUserAgent . "\r\n",
                        $headers
                    );
                } finally {
                    fclose($connection);
                }
            } finally {
                fclose($server);
            }
        }
    }

    public static function requestTypeProvider(): array
    {
        return [
            'JSON request with default' => [false, null],
            'multipart request with default' => [true, null],
            'JSON request with override' => [false, 'DiscordBot (https://example.com/my-app, 1.0.0)'],
            'multipart request with override' => [true, 'DiscordBot (https://example.com/my-app, 1.0.0)'],
        ];
    }
}
