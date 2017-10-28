<?php

namespace Discord\Embed;

/**
 * Class EmbedFooter
 *
 * @author  Scrummer <scrummer@labymod.net>
 * @package DiscordWebhooks\Embed
 */
class EmbedFooter extends AbstractEmbed
{
    /**
     * @param string $text
     *
     * @return EmbedFooter
     */
    public function setText( $text )
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @param string $iconUrl
     *
     * @return EmbedFooter
     */
    public function setIconUrl( $iconUrl )
    {
        $this->iconUrl = $iconUrl;

        return $this;
    }

    /**
     * @param string $proxyIconUrl
     *
     * @return EmbedFooter
     */
    public function setProxyIconUrl( $proxyIconUrl )
    {
        $this->proxyIconUrl = $proxyIconUrl;

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
            'text'           => $this->text,
            'icon_url'       => $this->iconUrl,
            'proxy_icon_url' => $this->proxyIconUrl
        ];
    }
}