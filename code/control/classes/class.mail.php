<?php

// Load mailer..
include(dirname(__file__) . '/class.smtp.php');
include(dirname(__file__) . '/class.phpmailer.php');
include(dirname(__file__) . '/class.parser.php');

class mailingSystem extends PHPMailer {

  public $vars = array();

  // Constructor..
  public function mailingSystem($smtp = array(), $headers = array(), $attachments = array()) {
    $this->mhost    = $smtp['smtp_host'];
    $this->mport    = $smtp['smtp_port'];
    $this->muser    = $smtp['smtp_user'];
    $this->mpass    = $smtp['smtp_pass'];
    $this->msec     = $smtp['smtp_security'];
    $this->malive   = (isset($smtp['alive']) ? 'yes' : 'no');
    $this->mheaders = $headers;
    $this->mattach  = $attachments;
  }

  // HTML wrapper for report emails..
  public function htmlWrapper($arr) {
    $psr  = new parser();
    $html = file_get_contents(MM_BASE_PATH . 'content/language/email-templates/html-wrapper.html');
    return str_replace(array(
      '{LANG}',
      '{DIR}',
      '{CHARSET}',
      '{TITLE}',
      '{HEADER}',
      '{CONTENT}',
      '{FOOTER}'
    ), array(
      $arr['global'][2],
      $arr['global'][1],
      $arr['global'][3],
      $arr['title'],
      $arr['header'],
      $psr->mswAutoLinkParser($arr['content']),
      mailingSystem::appendFooterToEmails('html')
    ), $html);
  }

  // Converts entities..for plain text
  public function convertChar($data, $type = 'html') {
    $find    = array(
      '&#039;',
      '&quot;',
      '&amp;',
      '&lt;',
      '&gt;'
    );
    $replace = array(
      '\'',
      '"',
      '&',
      '<',
      '>'
    );
    $data = htmlspecialchars_decode($data);
    if ($type == 'plain') {
      return str_replace($find, $replace, mswCleanData($data));
    } else {
      return mswCleanData($data);
    }
  }

  // Loads tags into array..
  public function addTag($placeholder, $data) {
    $this->vars[$placeholder] = mswSafeDisplay($data);
  }

  // Clears data vars..
  public function clearVars() {
    $this->vars = array();
  }

  // Converts tags..
  public function convertTags($data) {
    if (!empty($this->vars)) {
      foreach ($this->vars AS $tags => $value) {
        $data = str_replace($tags, $value, $data);
      }
    }
    return $data;
  }

  // Cleans spam/form injection input..
  public function injectionCleaner($data) {
    $find    = array(
      "\r",
      "\n",
      "%0a",
      "%0d",
      "content-type:",
      "Content-Type:",
      "BCC:",
      "CC:",
      "boundary=",
      "TO:",
      "bcc:",
      "to:",
      "cc:"
    );
    $replace = array();
    return str_replace($find, $replace, $data);
  }

  // Loads e-mail template..
  public function template($file) {
    $email_string = trim(file_get_contents($file));
    return ($email_string ? trim(mailingSystem::convertTags($email_string)) : die('An error occurred opening the <b>' . $file . '</b> file. Check that this file exists in the correct "templates/language/email-templates/" folder!'));
  }

  // Footer for free version..
  public function appendFooterToEmails($type) {
    $string = '';
    if (defined('LICENCE_VER')) {
      if (LICENCE_VER == 'unlocked') {
        return $string;
      }
      if ($type == 'html') {
        $string = '<span style="font-size:10px;font-family:verdana;display:block;margin-top:10px;background:#fff;padding:5px">----------------------<br>';
        $string .= '<span style="color:#000">Free Self Hosted MP3 Music Store. Powered by <a style="color:#000" href="http://www.maianmusic.com">Maian Music</a></span>';
        $string .= '</span>';
      } else {
        $string = mswDefineNewline() . mswDefineNewline() . '----------------------' . mswDefineNewline();
        $string .= 'Free Self Hosted MP3 Music Store' . mswDefineNewline();
        $string .= 'http://www.maianmusic.com';
      }
    }
    return $string;
  }

  // Sends mail..
  public function sendMail($data, $lang) {
    // Is mail activated? Set in 'control/defined.php'
    if (MAIL_ACTIVATE) {
      // Additional stripping of too many chars..
      // Helps to prevent mail header injections for spam..
      $to_name    = substr($data['to_name'], 0, 250);
      $to_email   = substr($data['to_email'], 0, 250);
      $from_name  = substr($data['from_name'], 0, 250);
      $from_email = substr($data['from_email'], 0, 250);
      $this->LE   = mswDefineNewline();
      $this->IsSMTP();
      $this->Port       = ($this->mport ? $this->mport : '25');
      $this->Host       = ($this->mhost ? $this->mhost : 'localhost');
      $this->SMTPAuth   = ($this->muser && $this->mpass ? true : false);
      $this->SMTPSecure = (in_array($this->msec, array(
        '',
        'tls',
        'ssl'
      )) ? $this->msec : '');
      // Keep connection alive..
      if ($this->malive == 'yes') {
        $this->SMTPKeepAlive = true;
      }
      $this->Username = $this->muser;
      $this->Password = $this->mpass;
      $this->From     = $from_email;
      $this->FromName = mailingSystem::convertChar(mswCleanData(mailingSystem::injectionCleaner($from_name)));
      $this->CharSet  = (isset($lang[3]) ? $lang[3] : 'utf-8');
      // Enable debug..
      if (MAIL_DEBUG) {
        $this->SMTPDebug = 2;
      }
      $this->ContentType = 'text/html';
      // XMailer header..
      if (MAIL_X_MAIL_HEADER) {
        $this->XMailer = MAIL_X_MAIL_HEADER;
      }
      $this->AddAddress($to_email, mailingSystem::convertChar(mswCleanData(mailingSystem::injectionCleaner($to_name))));
      // Reply to if set..
      if (isset($data['reply-to']) && $data['reply-to']) {
        $this->AddReplyTo($data['reply-to']);
      }
      // CC if set..
      if (isset($data['other']) && $data['other']) {
        $chopcc = array_map('trim',explode(',', $data['other']));
        foreach ($chopcc AS $mcc) {
          $this->addCC($mcc);
        }
      }
      $this->WordWrap = 1000;
      $this->Subject  = mailingSystem::convertChar(mswCleanData($data['subject']));
      if (isset($data['htmlWrap'])) {
        $this->MsgHTML(mswCleanData($data['msg']));
      } else {
        $this->MsgHTML(mswCleanData($data['msg']) . mailingSystem::appendFooterToEmails('html'));
      }
      // Plain text version of message..
      if (isset($data['plain'])) {
        $this->AltBody = mailingSystem::convertChar(mswCleanData($data['plain']), 'plain') . mailingSystem::appendFooterToEmails('plain');
      }
      // Custom headers..
      if (!empty($this->mheaders)) {
        for ($i = 0; $i < count($this->mheaders); $i++) {
          $this->AddCustomHeader($this->mheaders[$i][0] . ':' . $this->mheaders[$i][1]);
        }
      }
      // Attachments...
      if (!empty($this->mattach)) {
        for ($i = 0; $i < count($this->mattach); $i++) {
          $this->AddAttachment($this->mattach[$i], basename($this->mattach[$i]));
        }
      }
      // Send message..
      $this->Send();
      // Clear all recipient data..
      $this->ClearReplyTos();
      $this->ClearAllRecipients();
    }
  }

}

//---------------------------------------------------
// Check licence ver - please do not alter or change
//---------------------------------------------------

if (!defined('LICENCE_VER') || !class_exists('mswLic')) {
  die(@base64_decode('U3lzdGVtIGVycm9yLCBwbGVhc2UgY29udGFjdCBNUyBXb3JsZCBAIDxhIGhyZWY9Im1haWx0bzpzdXBwb3J0QG1haWFuc2NyaXB0d29ybGQuY28udWsiPnN1cHBvcnRAbWFpYW5zY3JpcHR3b3JsZC5jby51azwvYT48YnI+PGJyPlNvcnJ5IGZvciB0aGUgaW5jb252ZW5pZW5jZS4='));
}

?>
