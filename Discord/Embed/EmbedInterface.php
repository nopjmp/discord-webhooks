<?php

namespace Discord\Embed;

interface EmbedInterface
{
    /**
     * Converts the embed object to an array
     *
     * @return array
     */
    public function toArray();
}