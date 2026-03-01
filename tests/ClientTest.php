<?php

namespace DiscordWebhooks\Tests;

use DiscordWebhooks\Client;
use DiscordWebhooks\Embed;
use PHPUnit\Framework\TestCase;

class TestableClient extends Client
{
    public $lastRequestUrl;
    public $lastRequestPostFields;
    public $lastRequestHeaders;
    public $sendCalled = false;

    public function getUrl(): string { return $this->url; }
    public function getUsername() { return $this->username; }
    public function getAvatar() { return $this->avatar; }
    public function getMessage() { return $this->message; }
    public function getTts() { return $this->tts; }
    public function getEmbeds() { return $this->embeds; }
    public function getFiles(): array { return $this->files; }

    protected function executeRequest($url, $postFields, $headers)
    {
        $this->sendCalled = true;
        $this->lastRequestUrl = $url;
        $this->lastRequestPostFields = $postFields;
        $this->lastRequestHeaders = $headers;
    }
}

class ClientTest extends TestCase
{
    public function testConstructorSetsUrl(): void
    {
        $client = new TestableClient('https://discord.com/api/webhooks/test');
        $this->assertSame('https://discord.com/api/webhooks/test', $client->getUrl());
    }

    public function testConstructorInitializesEmptyFiles(): void
    {
        $client = new TestableClient('https://discord.com/api/webhooks/test');
        $this->assertSame([], $client->getFiles());
    }

    public function testUsername(): void
    {
        $client = new TestableClient('https://discord.com/api/webhooks/test');
        $client->username('TestBot');
        $this->assertSame('TestBot', $client->getUsername());
    }

    public function testAvatar(): void
    {
        $client = new TestableClient('https://discord.com/api/webhooks/test');
        $client->avatar('https://example.com/avatar.png');
        $this->assertSame('https://example.com/avatar.png', $client->getAvatar());
    }

    public function testMessage(): void
    {
        $client = new TestableClient('https://discord.com/api/webhooks/test');
        $client->message('Hello, world!');
        $this->assertSame('Hello, world!', $client->getMessage());
    }

    public function testTts(): void
    {
        $client = new TestableClient('https://discord.com/api/webhooks/test');
        $client->tts(true);
        $this->assertTrue($client->getTts());
    }

    public function testTtsDefaultFalse(): void
    {
        $client = new TestableClient('https://discord.com/api/webhooks/test');
        $client->tts();
        $this->assertFalse($client->getTts());
    }

    public function testEmbed(): void
    {
        $client = new TestableClient('https://discord.com/api/webhooks/test');
        $embed = new Embed();
        $embed->title('Test')->description('Description');
        $client->embed($embed);

        $embeds = $client->getEmbeds();
        $this->assertCount(1, $embeds);
        $this->assertSame('Test', $embeds[0]['title']);
        $this->assertSame('Description', $embeds[0]['description']);
    }

    /**
     * @requires extension curl
     */
    public function testAddFileWithExistingFile(): void
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'discord_test_');
        file_put_contents($tmpFile, 'test content');

        try {
            $client = new TestableClient('https://discord.com/api/webhooks/test');
            $client->addFile($tmpFile);
            $files = $client->getFiles();

            $this->assertCount(1, $files);
            $this->assertInstanceOf(\CURLFile::class, $files[0]);
        } finally {
            unlink($tmpFile);
        }
    }

    /**
     * @requires extension curl
     */
    public function testAddFileMissingFileThrowsException(): void
    {
        $this->expectException(\Exception::class);

        $client = new TestableClient('https://discord.com/api/webhooks/test');
        $client->addFile('/nonexistent/file.txt');
    }

    /**
     * @requires extension curl
     */
    public function testAddFileWithCustomFilenameAndMime(): void
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'discord_test_');
        file_put_contents($tmpFile, 'test content');

        try {
            $client = new TestableClient('https://discord.com/api/webhooks/test');
            $client->addFile($tmpFile, 'custom.txt', 'text/plain');
            $files = $client->getFiles();

            $this->assertCount(1, $files);
            $this->assertSame('custom.txt', $files[0]->getPostFilename());
            $this->assertSame('text/plain', $files[0]->getMimeType());
        } finally {
            unlink($tmpFile);
        }
    }

    /**
     * @requires extension curl
     * @requires PHP 8.1
     */
    public function testAddStringFile(): void
    {
        $client = new TestableClient('https://discord.com/api/webhooks/test');
        $client->addStringFile('file content', 'test.txt', 'text/plain');
        $files = $client->getFiles();

        $this->assertCount(1, $files);
        $this->assertInstanceOf(\CURLStringFile::class, $files[0]);
    }

    /**
     * @requires extension curl
     */
    public function testClearFiles(): void
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'discord_test_');
        file_put_contents($tmpFile, 'test content');

        try {
            $client = new TestableClient('https://discord.com/api/webhooks/test');
            $client->addFile($tmpFile);
            $this->assertCount(1, $client->getFiles());

            $client->clearFiles();
            $this->assertSame([], $client->getFiles());
        } finally {
            unlink($tmpFile);
        }
    }

    public function testFluentInterface(): void
    {
        $client = new TestableClient('https://discord.com/api/webhooks/test');
        $embed = new Embed();
        $embed->title('Test');

        $result = $client
            ->username('Bot')
            ->avatar('https://example.com/avatar.png')
            ->message('Hello')
            ->tts(false)
            ->embed($embed);

        $this->assertSame($client, $result);
    }

    public function testSendBuildsJsonPayload(): void
    {
        $client = new TestableClient('https://discord.com/api/webhooks/test');
        $client->username('Bot')->message('Hello')->tts(true)->send();

        $this->assertTrue($client->sendCalled);
        $this->assertSame('https://discord.com/api/webhooks/test', $client->lastRequestUrl);
        $this->assertSame(['Content-Type: application/json'], $client->lastRequestHeaders);

        $payload = json_decode($client->lastRequestPostFields, true);
        $this->assertSame('Bot', $payload['username']);
        $this->assertSame('Hello', $payload['content']);
        $this->assertTrue($payload['tts']);
    }

    /**
     * @requires extension curl
     */
    public function testSendBuildsMultipartPayloadWithFiles(): void
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'discord_test_');
        file_put_contents($tmpFile, 'test content');

        try {
            $client = new TestableClient('https://discord.com/api/webhooks/test');
            $client->message('Hello')->addFile($tmpFile)->send();

            $this->assertTrue($client->sendCalled);
            $this->assertSame(['Content-Type: multipart/form-data'], $client->lastRequestHeaders);
            $this->assertArrayHasKey('payload_json', $client->lastRequestPostFields);
            $this->assertInstanceOf(\CURLFile::class, $client->lastRequestPostFields[0]);

            $payload = json_decode($client->lastRequestPostFields['payload_json'], true);
            $this->assertSame('Hello', $payload['content']);
        } finally {
            unlink($tmpFile);
        }
    }

    public function testSendWithEmbedPayload(): void
    {
        $client = new TestableClient('https://discord.com/api/webhooks/test');
        $embed = new Embed();
        $embed->title('Test Title')->description('Test Desc');
        $client->message('Hello')->embed($embed)->send();

        $payload = json_decode($client->lastRequestPostFields, true);
        $this->assertCount(1, $payload['embeds']);
        $this->assertSame('Test Title', $payload['embeds'][0]['title']);
        $this->assertSame('Test Desc', $payload['embeds'][0]['description']);
    }

    public function testSendReturnsSelf(): void
    {
        $client = new TestableClient('https://discord.com/api/webhooks/test');
        $result = $client->message('test')->send();
        $this->assertSame($client, $result);
    }
}
