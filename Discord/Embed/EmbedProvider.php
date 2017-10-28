<?php

namespace Discord\Embed;

/**
 * Class EmbedProvider
 *
 * @author  Scrummer <scrummer@gmx.ch>
 * @package DiscordWebhooks\Embed
 */
class EmbedProvider extends AbstractEmbed
{
    /**
     * @param string $name
     *
     * @return EmbedProvider
     */
    public function setName( $name )
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $url
     *
     * @return EmbedProvider
     */
    public function setUrl( $url )
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Converts the embed object to an array
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'name' => $this->name,
            'url'  => $this->url
        ];
    }
}