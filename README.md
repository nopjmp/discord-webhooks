# discord-webhooks

Discord webhooks is a simple client for Discord's webhook API.

### Motivation

While this is probably "yet another" library for Discord's webhook system, I wanted to make my own. Discord doesn't support BitBucket's webhook system and the Slack compatible endpoint doesn't send a message.

## Getting Started

You can either copy the PHP file directly into your project or _preferable_ just use composer.

#### Composer require command
`composer require nopjmp/discord-webhooks`

## Usage

It is fairly easy to use. I'll throw in an example.

```php
use \DiscordWebhooks\Client;

$webhook = new Client('DISCORD_WEBHOOK_URL');
$webhook->name('Bot')->message('Hello, Human!')->send();
```

## License

The project is MIT licensed. To read the full license, open [LICENSE.md](LICENSE.md).

## Contributing

Pull requests and issues are open!
