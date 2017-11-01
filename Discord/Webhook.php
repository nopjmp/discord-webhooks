<?php

namespace Discord;

/**
 * Class Webhook
 *
 * Webhook generates the payload and sends the webhook payload to Discord
 */
class Webhook
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $avatar;

    /**
     * @var string
     */
    private $message;

    /**
     * @var array
     */
    private $embeds;

    /**
     * @var bool
     */
    private $tts;

    /**
     * @var array
     */
    private $file;

    /**
     * @var array
     */
    private $data = [];

    /**
     * @param string $url
     */
    public function __construct( $url )
    {
        $this->url = $url;
    }

    /**
     * @param bool $tts
     *
     * @return Webhook
     */
    public function setTts( $tts = false )
    {
        $this->tts = $tts;

        return $this;
    }

    /**
     * @param string $username
     *
     * @return Webhook
     */
    public function setUsername( $username )
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @param string $url
     *
     * @return Webhook
     */
    public function setAvatar( $url )
    {
        $this->avatar = $url;

        return $this;
    }

    /**
     * @param string $message
     *
     * @return Webhook
     */
    public function setMessage( $message )
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @param Embed $embed
     *
     * @return Webhook
     */
    public function setEmbed( $embed )
    {
        $this->embeds[] = $embed->toArray();

        return $this;
    }

    /**
     * @param File $file
     *
     * @return Webhook
     */
    public function setFile( $file )
    {
        $this->data['file'] = curl_file_create( $file->getFile(), null, $file->getFileName() );
        $this->file = $this->data;

        return $this;
    }

    /**
     * Send the Webhook
     *
     * @param bool $unsetFields
     *
     * @return Webhook
     * @throws \Exception
     */
    public function send( $unsetFields = false )
    {
        $payload = json_encode( [
            'username'   => $this->username,
            'avatar_url' => $this->avatar,
            'content'    => $this->message,
            'embeds'     => $this->embeds,
            'tts'        => $this->tts,
        ] );

        $ch = curl_init();

        curl_setopt( $ch, CURLOPT_URL, $this->url );
        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, ['Content-Type: multipart/form-data'] );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 2 );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 1 );
        curl_setopt( $ch, CURLOPT_HEADER, true );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, isset( $this->file ) ? $this->file : $payload );

        $result = curl_exec( $ch );
        // Check for errors and display the error message
        if ( $errno = curl_errno( $ch ) ) {
            $error_message = curl_strerror( $errno );
            throw new \Exception( "cURL error ({$errno}):\n {$error_message}" );
        }

        $json_result = json_decode( $result, true );

        if ( ($httpcode = curl_getinfo( $ch, CURLINFO_HTTP_CODE )) != 204 || ($httpcode = curl_getinfo( $ch, CURLINFO_HTTP_CODE )) != 200 ) {
            throw new \Exception( $httpcode . ':' . $result );
        }

        curl_close( $ch );
        if ( $unsetFields ) {
            $this->unsetFields();
        }

        return $this;
    }

    private function unsetFields()
    {
        foreach ( get_object_vars( $this ) as $var ) {
            unset( $var );
        }
    }
}
