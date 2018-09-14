<?php

class apis {

  var $settings;
  var $urls = array(
   'pushover' => 'https://api.pushover.net/1/messages.json'
  );

  public function pushover($msg, $subj) {
    $pushapi = $this->builder->params('pushover');
    if (isset($pushapi['pushover']['pushuser'],$pushapi['pushover']['pushtoken']) && function_exists('curl_init')) {
      if ($pushapi['pushover']['pushuser'] && $pushapi['pushover']['pushtoken']) {
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $this->urls['pushover']);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, array(
          'token' =>  $pushapi['pushover']['pushtoken'],
          'user' => $pushapi['pushover']['pushuser'],
          'message' => $msg,
          'title' => $subj
        ));
        $response = curl_exec($c);
        $json     = json_decode($response);
      }
    }
    return 'disable';
  }

}

?>