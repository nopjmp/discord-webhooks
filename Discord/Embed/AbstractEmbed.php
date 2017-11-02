<?php

namespace Discord\Embed;

/**
 * Class AbstractEmbed
 *
 * @author  Scrummer <scrummer@labymod.net>
 * @package DiscordWebhooks\Embed
 */
abstract class AbstractEmbed implements EmbedInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var int
     */
    protected $width;

    /**
     * @var int
     */
    protected $height;

    /**
     * @var string
     */
    protected $proxyUrl;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var string
     */
    protected $iconUrl;

    /**
     * @var string
     */
    protected $proxyIconUrl;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var bool
     */
    protected $inline;
}