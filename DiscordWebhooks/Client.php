<?php

namespace DiscordWebhooks;

/**
 * Client generates the payload and sends the webhook payload to Discord
 */
class Client
{
  protected $url = null;
  protected $name = null;
  protected $avatar = null;
  protected $message = null;

  function __construct($url)
  {
    $this->$url = $url;
  }

  function name($new_name)
  {
    $this->$name = $new_name;
    return $this;
  }

  function avatar($new_avatar)
  {
    $this->$avatar = $new_avatar;
    return $this;
  }

  function message($new_message)
  {
    $this->$message = $new_message;
    return $this;
  }

  function send()
  {
    $payload = array(
      'name' => $this->$name,
      'avatar_url' => $this->$avatar,
      'content' => $this->$message,
    );

    $data = json_encode($payload);

    $options = array(
      CURLOPT_URL => $this->$url,
      CURLOPT_POST => 1,
      CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_SSL_VERIFYHOST => true,
      CURLOPT_SSL_VERIFYPEER => true,
      CURLOPT_POSTFIELDS, $data,
    )

    $ch = curl_init();

    curl_setopt_array($ch, $options);

    $result = curl_exec($ch);
    $json_result = json_decode($result, true);

    if (curl_getinfo($ch, CURLINFO_HTTP_CODE) != 204)
    {
      throw new Exception($output['message']);
    }

    curl_close($ch);
    return $this;
  }
}


 ?>
