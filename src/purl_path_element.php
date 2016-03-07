<?php
namespace Drupal\purl;

class purl_path_element {
  public $processor;
  public $value;
  public $provider;
  public $id;

  function __construct($processor, $value, $provider, $id) {
    $this->processor = $processor;
    $this->value = $value;
    $this->provider = $provider;
    $this->id = $id;
  }
}
