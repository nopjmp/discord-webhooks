<?php

namespace DiscordWebhooks\Tests;

use DiscordWebhooks\Embed;
use PHPUnit\Framework\TestCase;

class EmbedTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $embed = new Embed();
        $array = $embed->toArray();

        $this->assertSame('rich', $array['type']);
        $this->assertNull($array['title']);
        $this->assertNull($array['description']);
        $this->assertNull($array['url']);
        $this->assertNull($array['timestamp']);
        $this->assertNull($array['color']);
        $this->assertNull($array['footer']);
        $this->assertNull($array['image']);
        $this->assertNull($array['thumbnail']);
        $this->assertNull($array['author']);
        $this->assertNull($array['fields']);
    }

    public function testTitle(): void
    {
        $embed = new Embed();
        $embed->title('Test Title');
        $array = $embed->toArray();

        $this->assertSame('Test Title', $array['title']);
    }

    public function testTitleWithUrl(): void
    {
        $embed = new Embed();
        $embed->title('Test Title', 'https://example.com');
        $array = $embed->toArray();

        $this->assertSame('Test Title', $array['title']);
        $this->assertSame('https://example.com', $array['url']);
    }

    public function testDescription(): void
    {
        $embed = new Embed();
        $embed->description('A description');
        $this->assertSame('A description', $embed->toArray()['description']);
    }

    public function testTimestamp(): void
    {
        $embed = new Embed();
        $embed->timestamp('2024-01-01T00:00:00Z');
        $this->assertSame('2024-01-01T00:00:00Z', $embed->toArray()['timestamp']);
    }

    public function testColorInteger(): void
    {
        $embed = new Embed();
        $embed->color(16711680);
        $this->assertSame(16711680, $embed->toArray()['color']);
    }

    public function testColorHexWithHash(): void
    {
        $embed = new Embed();
        $embed->color('#FF0000');
        $this->assertSame(0xFF0000, $embed->toArray()['color']);
    }

    public function testColorHexWithoutHash(): void
    {
        $embed = new Embed();
        $embed->color('00FF00');
        $this->assertSame(0x00FF00, $embed->toArray()['color']);
    }

    public function testUrl(): void
    {
        $embed = new Embed();
        $embed->url('https://example.com');
        $this->assertSame('https://example.com', $embed->toArray()['url']);
    }

    public function testFooter(): void
    {
        $embed = new Embed();
        $embed->footer('Footer text', 'https://example.com/icon.png');
        $footer = $embed->toArray()['footer'];

        $this->assertSame('Footer text', $footer['text']);
        $this->assertSame('https://example.com/icon.png', $footer['icon_url']);
    }

    public function testFooterWithoutIcon(): void
    {
        $embed = new Embed();
        $embed->footer('Footer text');
        $footer = $embed->toArray()['footer'];

        $this->assertSame('Footer text', $footer['text']);
        $this->assertSame('', $footer['icon_url']);
    }

    public function testImage(): void
    {
        $embed = new Embed();
        $embed->image('https://example.com/image.png');
        $this->assertSame(['url' => 'https://example.com/image.png'], $embed->toArray()['image']);
    }

    public function testThumbnail(): void
    {
        $embed = new Embed();
        $embed->thumbnail('https://example.com/thumb.png');
        $this->assertSame(['url' => 'https://example.com/thumb.png'], $embed->toArray()['thumbnail']);
    }

    public function testAuthor(): void
    {
        $embed = new Embed();
        $embed->author('Author Name', 'https://example.com', 'https://example.com/avatar.png');
        $author = $embed->toArray()['author'];

        $this->assertSame('Author Name', $author['name']);
        $this->assertSame('https://example.com', $author['url']);
        $this->assertSame('https://example.com/avatar.png', $author['icon_url']);
    }

    public function testAuthorNameOnly(): void
    {
        $embed = new Embed();
        $embed->author('Author Name');
        $author = $embed->toArray()['author'];

        $this->assertSame('Author Name', $author['name']);
        $this->assertSame('', $author['url']);
        $this->assertSame('', $author['icon_url']);
    }

    public function testField(): void
    {
        $embed = new Embed();
        $embed->field('Field Name', 'Field Value', true);
        $fields = $embed->toArray()['fields'];

        $this->assertCount(1, $fields);
        $this->assertSame('Field Name', $fields[0]['name']);
        $this->assertSame('Field Value', $fields[0]['value']);
        $this->assertTrue($fields[0]['inline']);
    }

    public function testMultipleFieldsAccumulate(): void
    {
        $embed = new Embed();
        $embed->field('Field 1', 'Value 1');
        $embed->field('Field 2', 'Value 2', true);
        $embed->field('Field 3', 'Value 3');
        $fields = $embed->toArray()['fields'];

        $this->assertCount(3, $fields);
        $this->assertSame('Field 1', $fields[0]['name']);
        $this->assertSame('Field 2', $fields[1]['name']);
        $this->assertSame('Field 3', $fields[2]['name']);
        $this->assertFalse($fields[0]['inline']);
        $this->assertTrue($fields[1]['inline']);
    }

    public function testFluentInterface(): void
    {
        $embed = new Embed();
        $result = $embed
            ->title('Title')
            ->description('Desc')
            ->color('#FF0000')
            ->url('https://example.com')
            ->timestamp('2024-01-01T00:00:00Z')
            ->footer('Footer')
            ->image('https://example.com/img.png')
            ->thumbnail('https://example.com/thumb.png')
            ->author('Author')
            ->field('Name', 'Value');

        $this->assertSame($embed, $result);
    }
}
