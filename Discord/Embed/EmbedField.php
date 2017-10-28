<?php

namespace Discord\Embed;

/**
 * Class EmbedField
 *
 * @author  Scrummer <scrummer@labymod.net>
 * @package DiscordWebhooks\Embed
 */
class EmbedField extends AbstractEmbed
{
    /**
     * @param string $name
     *
     * @return EmbedField
     */
    public function setName( $name )
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $value
     *
     * @return EmbedField
     */
    public function setValue( $value )
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @param bool $inline
     *
     * @return EmbedField
     */
    public function setInline( $inline )
    {
        $this->inline = $inline;

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
            'name'   => $this->name,
            'value'  => $this->value,
            'inline' => $this->inline
        ];
    }
}