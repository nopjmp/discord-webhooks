# discord-webhooks

Discord webhooks is a simple client for Discord's webhook API.

### Motivation

While this is probably "yet another" library for Discord's webhook system, I wanted to make my own. Discord doesn't support BitBucket's webhook system and the Slack compatible endpoint doesn't send a message.

## Getting Started

Requires PHP 8.2 or later with the cURL extension.

You can either copy the PHP file directly into your project or _preferable_ just use composer.

#### Composer require command
`composer require nopjmp/discord-webhooks`

## Usage

It is fairly easy to use. I'll throw in an example.

```php
use \DiscordWebhooks\Client;
use \DiscordWebhooks\Embed;

$webhook = new Client('DISCORD_WEBHOOK_URL');
$embed = new Embed();

$embed->description('This is an embed');

$webhook->username('Bot')->message('Hello, Human!')->embed($embed)->send();
```

Requests have a 10-second connection timeout and a 30-second overall timeout.
The overall timeout includes connection time and file uploads. Override these
limits with positive integers in seconds:

```php
$webhook = new Client('DISCORD_WEBHOOK_URL', timeout: 60, connectTimeout: 5);
```

`send()` throws an exception if a timeout expires.

Requests identify this library and its version in the User-Agent header. Override
the header to identify your application:

```php
$webhook->userAgent('DiscordBot (https://example.com/my-app, 1.0.0)');
```

Blank values, carriage returns, newlines, and null bytes throw `InvalidArgumentException`.

Example of sending files:
```
$webhook->addFile("/path/to/file")
        ->addStringFile("Hello World!", "hello_world.txt")
        ->send();
```

## License

The project is MIT licensed. To read the full license, open [LICENSE.md](LICENSE.md).

## Contributing

Pull requests and issues are open!
