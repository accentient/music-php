<?php

class rss {

  private $options = array('xmlver' => '1.0', 'encoding' => 'utf-8', 'version' => '2.0', 'lang' => 'en-us');
  public $settings;

  // Starts rss channel..
  public function open() {
    $s  = '<rss version="' . $this->options['version'] . '" xmlns:atom="http://www.w3.org/2005/Atom"><channel>';
    $s2 = '<?xml version="' . $this->options['xmlver'] . '" encoding="' . $this->options['encoding'] . '" ?>';
    return trim($s2 . $s);
  }

  // Loads data into feed..
  public function item($data = array()) {
    $s = '<item>
     <title>' . rss::render($data['title']) . '</title>
     <link>' . $data['link'] . '</link>
     <pubDate>' . $data['date'] . '</pubDate>
     <guid>' . $data['link'] . '</guid>
     <description><![CDATA[' . rss::tags($data['desc']) . ']]></description>
    </item>
    ';
    return $s;
  }

  // Loads feed info..
  public function feed($data = array()) {
    $s = '<title>' . rss::render($data['title']) . '</title>
    <link>' . $data['link'] . '</link>
    <description>' . rss::render($data['desc']) . '</description>
    <lastBuildDate>' . $data['date'] . '</lastBuildDate>
    <language>' . $this->options['lang'] . '</language>
    <generator>' . rss::render($data['site']) . '</generator>
    <atom:link href="' . $data['self'] . '" rel="self" type="application/atom+xml" />
    ';
    return $s;
  }

  // Closes rss channel..
  public function close() {
    return '</channel></rss>';
  }

  // Renders feed data..
  public function render($data, $clean_tags = false) {
    if ($clean_tags) {
      $data = rss::tags($data);
    }
    return '<![CDATA[' . mswCleanData($data) . ']]>';
  }

  // Format or remove tags
  // Add your own code if needed. May be updated in future version..
  public function tags($data) {
    return $data;
  }

}

?>