<?php
// $Id$

/**
 * Processors can inspect and manipulate various parts of a request's URI.
 */
interface purl_processor {

  /**
   * Return the method the processor users.
   *
   * @return string, machine name of the method.
   */
  public function method();

  /**
   * Provide a description of processor for the end user
   *
   * @return string description.
   */
  public function description();

  /**
   * Detect the the processor value for the current page request
   *
   * @return value that can be pased to the parse step.
   */
  public function detect();

  /**
   * Detects processor in the passed 'value'.
   *
   * @param $valid_values
   * @param $value
   * @return an array of purl_path_element objects
   */
  public function parse($valid_values, $value);

  /**
   * Used to provide compatibility with the path alias system.
   *
   * @param $value.
   *   detected value, by reference so that processors that can remove
   *   themselves is a method can have more than on value.
   * @param $element.
   *   purl_path_element
   */
  public function adjust(&$value, $element);

  /**
   * Responsible for rewriting outgoing links. Note: it's this functions
   * job to make sure it doesn't alter a link that has already been
   * treated.
   *
   * This must also check $options['purl']['disabled'] and
   * $options['purl']['remove']. The _purl_skip() method is helpful for this.
   *
   * @param $path
   *   string, by-reference the path to modify.
   * @param $options
   *   See url() docs
   * @param $element
   *   The element to add to the path.
   */
  public function rewrite(&$path, &$options, $element);
}

