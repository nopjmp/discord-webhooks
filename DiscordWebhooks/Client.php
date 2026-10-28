<?php

namespace DiscordWebhooks;

/**
 * Client generates the payload and sends the webhook payload to Discord
 */
class Client
{
  public const VERSION = '0.5.0-dev';

  protected $url;
  protected $username;
  protected $avatar;
  protected $message;
  protected $embeds;
  protected $tts;
  protected $files;
  protected $timeout;
  protected $connectTimeout;
  protected $userAgent = 'DiscordBot (https://github.com/nopjmp/discord-webhooks, ' . self::VERSION . ')';

  public function __construct($url, int $timeout = 30, int $connectTimeout = 10)
  {
    if ($timeout <= 0 || $connectTimeout <= 0) {
      throw new \InvalidArgumentException('Timeouts must be positive integers in seconds.');
    }
    $this->url = $url;
    $this->files = array();
    $this->timeout = $timeout;
    $this->connectTimeout = $connectTimeout;
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

  public function userAgent(string $userAgent): self
  {
    if (trim($userAgent) === '' || strpbrk($userAgent, "\r\n\0") !== false) {
      throw new \InvalidArgumentException('User-Agent must not be blank or contain carriage returns, newlines, or null bytes.');
    }
    $this->userAgent = $userAgent;
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
    // CURLStringFile does not accept null as mime type, so fall back to its own default
    $this->files[] = new \CURLStringFile($data, $posted_filename, $mime_type ?? 'application/octet-stream');
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
    ), JSON_THROW_ON_ERROR);

    if ($this->files) {
      $postFields = array('payload_json' => $payload);
      foreach ($this->files as $index => $file) {
        $postFields["files[$index]"] = $file;
      }
      $headers = array('Content-Type: multipart/form-data');
    } else {
      $postFields = $payload;
      $headers = array('Content-Type: application/json');
    }

    $this->executeRequest($this->url, $postFields, $headers);

    return $this;
  }

  protected function executeRequest($url, $postFields, $headers)
  {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connectTimeout);
    curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

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
  }
}
