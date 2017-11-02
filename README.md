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
<?php

use \Discord\Webhook;
use \Discord\Embed;

$webhook = new Webhook( 'DISCORD_WEBHOOK_URL' );
$embed = new Embed();

$embed->setDescription( 'This is an embed' );

$webhook->setUsername( 'Bot' )->setMessage( 'Hello, Human!' )->setEmbed( $embed )->send();
```

To style your embeds properly, you can have a look at this website: [https://cog-creators.github.io/discord-embed-sandbox/](https://cog-creators.github.io/discord-embed-sandbox/)\
Maybe the site can help you structuring your embeds properly ;)

## Advanced example

Now let's create a cool embed like this:\
![](http://scrummer.de/ss/K2017dR39.png)

Aaaaaand here's the code:
```php
<?php

use Discord\Webhook;

$wh = new Webhook( 'YOUR_WEBHOOK_URL' );
$embed = new \Discord\Embed();
$thumbnail = new \Discord\Embed\EmbedThumbnail();
$author = new \Discord\Embed\EmbedAuthor();
$field1 = new \Discord\Embed\EmbedField();
$field2 = new \Discord\Embed\EmbedField();
$field3 = new \Discord\Embed\EmbedField();
$footer = new \Discord\Embed\EmbedFooter();

$thumbnail->setUrl( 'https://pbs.twimg.com/media/C--1DR0UIAEr6Bw.png' );
$author
    ->setName( 'Discord' )
    ->setIconUrl( 'https://discordapp.com/assets/28174a34e77bb5e5310ced9f95cb480b.png' )
    ->setUrl( 'https://discordapp.com/' );
$field1
    ->setName( 'Field 1' )
    ->setValue( 'Some cool text' )
    ->setInline( true );
$field2
    ->setName( 'Field 2' )
    ->setValue( 'Another cool text' )
    ->setInline( true );
$field3
    ->setName( 'Field 3' )
    ->setValue( 'A full width field where I can write some more text' );
$footer->setText( 'Here goes the footer' );
$embed
    ->setTitle( 'This is my title' )
    ->setDescription( 'Another fancy description' )
    ->setColor( 15158332 )
    ->setThumbnail( $thumbnail )
    ->setAuthor( $author )
    ->setField( $field1 )
    ->setField( $field2 )
    ->setField( $field3 )
    ->setFooter( $footer );

$wh
    ->setUsername( 'Fancy Bot' )
    ->setAvatar( 'https://pbs.twimg.com/media/C51iiP9UYAIPpWP.png' )
    ->setEmbed( $embed )
    ->send();
```

Here's the official color list to colorize your embeds:
```javascript
DEFAULT: 0,
AQUA: 1752220,
GREEN: 3066993,
BLUE: 3447003,
PURPLE: 10181046,
GOLD: 15844367,
ORANGE: 15105570,
RED: 15158332,
GREY: 9807270,
DARKER_GREY: 8359053,
NAVY: 3426654,
DARK_AQUA: 1146986,
DARK_GREEN: 2067276,
DARK_BLUE: 2123412,
DARK_PURPLE: 7419530,
DARK_GOLD: 12745742,
DARK_ORANGE: 11027200,
DARK_RED: 10038562,
DARK_GREY: 9936031,
LIGHT_GREY: 12370112,
DARK_NAVY: 2899536
```

## License

The project is MIT licensed. To read the full license, open [LICENSE.md](LICENSE.md).

## Contributing

Pull requests and issues are open!
