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
  protected $file;

  protected $data = array();
  public function __construct($url)
  {
    $this->url = $url;
  }

  public function username($username)
  {
    $this->username = $username;
    return $this;
  }

  public function tts($tts = false) {
    $this->tts = $tts;
    return $this;
  }
  public function file($file) {
      $this->data['file'] = curl_file_create($file->getFile(), null, $file->getName());
      $this->file = $this->data;
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

  public function send()
  {
    $payload = json_encode(array(
      'username' => $this->username,
      'avatar_url' => $this->avatar,
      'content' => $this->message,
      'embeds' => $this->embeds,
      'tts' => $this->tts,
      //'files' => $this->file
    ));
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $this->url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: multipart/form-data']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    if (isset($this->file)) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->file);
    } else {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    }

    $result = curl_exec($ch);
    // Check for errors and display the error message
    if($errno = curl_errno($ch)) {
      $error_message = curl_strerror($errno);
      throw new \Exception("cURL error ({$errno}):\n {$error_message}");
    }
    $json_result = json_decode($result, true);
    if (($httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE)) != 200 || ($httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE)) != 204)
    {
      throw new \Exception($httpcode . ':' . $result);
    }
    curl_close($ch);
    return $this;
  }
  private static function phraseHeaders($response)
  {
    $headers = [];
    foreach (explode("\r\n", substr($response, 0, strpos($response, "\r\n\r\n"))) as $i => $line)
      if ($i === 0) $headers['http_code'] = $line;
      else {
        list ($key, $value) = explode(': ', $line);
        $key = strtolower(str_replace("-", '_', $key));
        $headers[$key] = $value;
      }
    return $headers;
  }
}
