<?php

namespace DiscordWebhooks;

/**
 * Client generates the payload and sends the webhook payload to Discord
 */
class Client
{
  protected $url;
  protected $username;
  protected $avatar;
  protected $message;
  protected $embeds;
  protected $tts;
  protected $files;

  public function __construct($url)
  {
    $this->url = $url;
    $this->files = array();
  }

  public function tts($tts = false) {
    $this->tts = $tts;
    return $this;
  }
  public function username($username)
  {
    $this->username = $username;
    return $this;
  }

  public function avatar($new_avatar)
  {
    $this->avatar = $new_avatar;
    return $this;
  }

  public function message($new_message)
  {
    $this->message = $new_message;
    return $this;
  }

  public function embed($embed) {
    $this->embeds[] = $embed->toArray();
    return $this;
  }

  public function addFile($file_path, $posted_filename = null, $mime_type = null) {
    if (!file_exists($file_path)) {
      throw new \Exception("$file_path: File not found.");
    }
    if (!$posted_filename) {
      $posted_filename = basename($file_path);
    }
    $this->files[] = new \CURLFile($file_path, $mime_type, $posted_filename);
    return $this;
  }

  public function addStringFile($data, $posted_filename, $mime_type = null) {
    $this->files[] = new \CURLStringFile($data, $posted_filename, $mime_type);
    return $this;
  }

  public function clearFiles() {
    $this->files = array();
    return $this;
  }

  public function send()
  {
    $payload = json_encode(array(
      'username' => $this->username,
      'avatar_url' => $this->avatar,
      'content' => $this->message,
      'embeds' => $this->embeds,
      'tts' => $this->tts,
    ));

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $this->url);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    if ($this->files) {
      $multipart_payload = $this->files;
      $multipart_payload['payload_json'] = $payload;
      curl_setopt($ch, CURLOPT_POSTFIELDS, $multipart_payload);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
    } else {
      curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    }

    $result = curl_exec($ch);
    // Check for errors and display the error message
    if($errno = curl_errno($ch)) {
      $error_message = curl_strerror($errno);
      throw new \Exception("cURL error ({$errno}):\n {$error_message}");
    }

    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($httpcode != 204 && $httpcode != 200)
    {
      throw new \Exception($httpcode . ':' . $result);
    }

    curl_close($ch);
    return $this;
  }
}
