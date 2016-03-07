<?php
namespace Drupal\purl;

/**
 * Specialized cache for storing modifier information.
 */
class purl_cache {

  protected $cache = array();

  function __construct() {
    foreach (_purl_options() as $k => $v) {
      $this->cache[$k] = array();
    }
  }

  /**
   * @param $method
   *   The method to add to the cache for
   * @param $item
   *   Either a integer|string, or keyed array to add
   * @param $merge
   *   Preserve keys and merge into cache for method.
   */
  public function add($method, $item, $merge = true) {
    if (is_array($item) && $merge) {
      // Need to preserve keys so we use the '+' array operator.
      if (!isset($this->cache[$method]) && count($item) >= 1) {
        $this->cache[$method] = array();
      }
      if (count($item) >= 1) {
        $this->cache[$method] = $this->cache[$method] + $item;
      }
    }
    else {
      $this->cache[$method][] = $item;
    }
    return $this;
  }

  /**
   * @param $method
   *   The method to retrieve from the cache for.
   * @param $id
   *   Optional key of the required info.
   *
   * @return the desired info or false if an id doesn't exist.
   */
  public function get($method = false, $id = false) {
    if ($method !== false && $id !== false) {
      return (isset($this->cache[$method][$id]) ? $this->cache[$method][$id] : false);
    }
    elseif ($method !== false) {
      return isset($this->cache[$method]) ? $this->cache[$method] : array();
    }
    else {
      return $this->cache;
    }
  }

  /**
   * @param $e
   *   The path element for which to fire the provider handler.
   */
  public function set($e) {
    if ($e->provider && $e->id) {
      $providers = purl_providers();
      $callback = $providers[$e->provider]['callback'];
      if (function_exists($callback)) {
        $args = array();
        if (isset($providers[$e->provider]['callback arguments'])) {
          $args = $providers[$e->provider]['callback arguments'];
        }
        $args[] = $e->id;
        call_user_func_array($callback, $args);
      }
    }
    return $this;
  }

  /**
   * @param $method
   *   Optional method to remove the cache for.
   * @param $provider
   *   Optional provider to remove the cache for.
   */
  public function remove($method = NULL, $provider = NULL) {
    if (isset($provider)) {
      $methods = isset($method) ? array($method) : array_keys($this->cache);
      foreach ($methods as $method) {
        if (isset($this->cache[$method])) {
          foreach ($this->cache[$method] as $key => $item) {
            if (is_object($item) && !empty($item->provider) && $item->provider == $provider) {
              unset($this->cache[$method][$key]);
            }
          }
        }
      }
    }
    else if (isset($method)) {
      if (isset($this->cache[$method])) {
        unset($this->cache[$method]);
      }
    }
    return $this;
  }
}
