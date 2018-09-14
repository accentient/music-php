<?php

class logRoutines {

  public $datetime;

  // Enable logs and set logs path..
  public $config = array('enabled' => 'yes', 'folder' => 'logs');

  // Constructor..
  public function logRoutines($en = '', $fldr = '', $lang = array()) {
    if ($en) {
      $this->config['enabled'] = $en;
    }
    if ($fldr) {
      $this->config['folder'] = $fldr;
    }
    if (!empty($lang)) {
      $this->lang = $lang;
    }
  }

  // Logs messages..
  public function write($action, $file = '') {
    if ($this->config['enabled'] == 'yes') {
      if (file_exists(PATH . $this->config['folder'] . '/' . $file . '.txt')) {
        $message = (isset($this->lang[40]) ? $this->lang[40] : 'Logged') . ': ' . $this->datetime->dateTimeDisplay($this->datetime->utcTime(), 'j F Y') . ' @ ' . $this->datetime->dateTimeDisplay($this->datetime->utcTime(), 'H:iA') . mswDefineNewline();
        $message .= (isset($this->lang[41]) ? $this->lang[41] : 'Action/Info') . ': ' . rtrim($action);
        $message .= mswDefineNewline() . mswDefineNewline() . '= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =' . mswDefineNewline() . mswDefineNewline();
      } else {
        $message = (isset($this->lang[42]) ? $this->lang[42] : 'Routine Description') . ': ' . rtrim($action) . ' @ ' . date('j F Y H:i:s');
        $message .= mswDefineNewline() . mswDefineNewline() . '= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =' . mswDefineNewline() . mswDefineNewline();
      }
      // Attempt to create log directory if it doesn`t exist..
      if (!is_dir(PATH . $this->config['folder'])) {
        $oldumask = @umask(0);
        @mkdir(PATH . $this->config['folder'], 0777);
        @umask($oldumask);
      }
      if (is_dir(PATH . $this->config['folder']) && is_writeable(PATH . $this->config['folder']) && function_exists('file_put_contents')) {
        @file_put_contents(PATH . $this->config['folder'] . '/' . $file . '.txt', $message, FILE_APPEND);
      }
    }
  }

}

?>