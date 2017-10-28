<?php

namespace Discord;

/**
 * Class Client
 *
 * Client generates the payload and sends the webhook payload to Discord
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
     * @param string $url
     */
    public function __construct( $url )
    {
        $this->url = $url;
    }

    /**
     * @param bool $tts
     *
     * @return Client
     */
    public function setTts( $tts = false )
    {
        $this->tts = $tts;

        return $this;
    }

    /**
     * @param string $username
     *
     * @return Client
     */
    public function setUsername( $username )
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @param string $newAvatar
     *
     * @return Client
     */
    public function setAvatar( $newAvatar )
    {
        $this->avatar = $newAvatar;

        return $this;
    }

    /**
     * @param string $newMessage
     *
     * @return Client
     */
    public function setMessage( $newMessage )
    {
        $this->message = $newMessage;

        return $this;
    }

    /**
     * @param Embed $embed
     *
     * @return Client
     */
    public function setEmbed( $embed )
    {
        $this->embeds[] = $embed->toArray();

        return $this;
    }

    public function send()
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
        curl_setopt( $ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json'] );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 2 );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 1 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );

        $result = curl_exec( $ch );
        // Check for errors and display the error message
        if ( $errno = curl_errno( $ch ) ) {
            $error_message = curl_strerror( $errno );
            throw new \Exception( "cURL error ({$errno}):\n {$error_message}" );
        }

        $json_result = json_decode( $result, true );

        if ( ($httpcode = curl_getinfo( $ch, CURLINFO_HTTP_CODE )) != 204 ) {
            throw new \Exception( $httpcode . ':' . $result );
        }

        curl_close( $ch );
        $this->unsetFields();

        return $this;
    }

    private function unsetFields()
    {
        foreach ( get_object_vars( $this ) as $var ) {
            unset( $var );
        }
    }
}
