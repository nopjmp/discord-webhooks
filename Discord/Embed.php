<?php

namespace Discord;

use DateTime;
use Discord\Embed\EmbedAuthor;
use Discord\Embed\EmbedField;
use Discord\Embed\EmbedFooter;
use Discord\Embed\EmbedImage;
use Discord\Embed\EmbedInterface;
use Discord\Embed\EmbedProvider;
use Discord\Embed\EmbedThumbnail;
use Discord\Embed\EmbedVideo;

/**
 * Class Embed
 *
 * Embed is an embed object to be included in a webhook message
 */
class Embed implements EmbedInterface
{
    /**
     * @var string $title
     */
    private $title;

    /**
     * @var string $type
     */
    private $type = "rich";

    /**
     * @var string $description
     */
    private $description;

    /**
     * @var string $url
     */
    private $url;

    /**
     * @var int $timestamp
     */
    private $timestamp;

    /**
     * @var int $color
     */
    private $color;

    /**
     * @var EmbedFooter
     */
    private $footer;

    /**
     * @var EmbedImage
     */
    private $image;

    /**
     * @var EmbedThumbnail
     */
    private $thumbnail;

    /**
     * @var EmbedVideo
     */
    private $video;

    /**
     * @var EmbedProvider
     */
    private $provider;

    /**
     * @var EmbedAuthor
     */
    private $author;

    /**
     * @var array
     */
    private $fields;

    /**
     * Constructor.
     *
     * @param string $url
     */
    public function __construct( $url = '' )
    {
        $this->url = $url;
    }

    /**
     * @param string $title
     *
     * @return Embed
     */
    public function setTitle( $title )
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param string $description
     *
     * @return Embed
     */
    public function setDescription( $description )
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param DateTime $dateTimeObject
     *
     * @return Embed
     */
    public function setTimestamp( DateTime $dateTimeObject )
    {
        $this->timestamp = $dateTimeObject->getTimestamp();

        return $this;
    }

    /**
     * @param int $color
     *
     * @return Embed
     */
    public function setColor( $color )
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @param string $url
     *
     * @return Embed
     */
    public function setUrl( $url )
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @param EmbedFooter $footer
     *
     * @return Embed
     */
    public function setFooter( EmbedFooter $footer )
    {
        $this->footer = $footer->toArray();

        return $this;
    }

    /**
     * @param EmbedImage $image
     *
     * @return Embed
     */
    public function setImage( EmbedImage $image )
    {
        $this->image = $image->toArray();

        return $this;
    }

    /**
     * @param EmbedThumbnail $thumbnail
     *
     * @return Embed
     */
    public function setThumbnail( EmbedThumbnail $thumbnail )
    {
        $this->thumbnail = $thumbnail->toArray();

        return $this;
    }

    /**
     * @param EmbedAuthor $author
     *
     * @return Embed
     */
    public function setAuthor( EmbedAuthor $author )
    {
        $this->author = $author->toArray();

        return $this;
    }

    /**
     * @param EmbedVideo $video
     *
     * @return Embed
     */
    public function setVideo( $video )
    {
        $this->video = $video->toArray();

        return $this;
    }

    /**
     * @param EmbedProvider $provider
     *
     * @return Embed
     */
    public function setProvider( $provider )
    {
        $this->provider = $provider->toArray();

        return $this;
    }

    /**
     * @param EmbedField $field
     *
     * @return $this
     */
    public function setField( EmbedField $field )
    {
        $this->fields[] = $field->toArray();

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
            'title'       => $this->title,
            'type'        => $this->type,
            'description' => $this->description,
            'url'         => $this->url,
            'color'       => $this->color,
            'footer'      => $this->footer,
            'image'       => $this->image,
            'thumbnail'   => $this->thumbnail,
            'author'      => $this->author,
            'fields'      => $this->fields,
            'provider'    => $this->provider,
            'video'       => $this->video
        ];
    }
}
