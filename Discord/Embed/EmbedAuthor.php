<?php

namespace Discord\Embed;

/**
 * Class EmbedAuthor
 *
 * @author  Scrummer <scrummer@labymod.net>
 * @package DiscordWebhooks\Embed
 */
class EmbedAuthor extends AbstractEmbed
{
    /**
     * @param string $name
     *
     * @return EmbedAuthor
     */
    public function setName( $name )
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $url
     *
     * @return EmbedAuthor
     */
    public function setUrl( $url )
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @param string $iconUrl
     *
     * @return EmbedAuthor
     */
    public function setIconUrl( $iconUrl )
    {
        $this->iconUrl = $iconUrl;

        return $this;
    }

    /**
     * @param string $proxyIconUrl
     *
     * @return EmbedAuthor
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
            'name'           => $this->name,
            'url'            => $this->url,
            'icon_url'       => $this->iconUrl,
            'proxy_icon_url' => $this->proxyIconUrl
        ];
    }
}