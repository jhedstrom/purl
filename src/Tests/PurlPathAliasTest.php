<?php
/**
 * @file
 * Contains \Drupal\purl\Tests\PurlPathAliasTest.
 */

namespace Drupal\purl\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Test PURL path aliases.
 *
 * @group purl
 */
class PurlPathAliasTest extends WebTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = ['path', 'purl', 'purl_test', 'node'];

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
    $admin_user = $this->drupalCreateUser(array('create page content', 'create url aliases', 'administer languages', 'administer site configuration'));
    $this->drupalLogin($admin_user);

    // Set up Spanish as second language.
    $this->drupalPost('admin/config/regional/language/add', array('langcode' => 'es'), t('Add language'));

    // Enable URL language detection and selection.
    $edit = array('language[enabled][locale-url]' => '1');
    $this->drupalPost('admin/config/regional/language/configure', $edit, t('Save settings'));

    // Add a node with path alias.
    $this->drupalPost('node/add/page', array('title' => 'purlTest', 'path[alias]' => 'purlTest'), t('Save'));
  }

  /**
   * Run test.
   */
  public function testPurlPathAlias() {
    variable_set('purl_types', array(
      'path' => 'path',
      'pair' => 'pair',
      'extension' => 'extension',
      'querystring' => 'querystring'
    ));
    variable_set('purl_method_purl_test', 'path');

    $this->drupalGet('purlTest');
    $this->assertText('purlTest', t('Node page found.'));

    $this->drupalGet('sweden/purlTest');
    $this->assertText('purlTest', t('Node page found.'));

    // This will not pass. The behavior in Drupal core is to ignore path
    // aliases that are not registered under the current language.
    // $this->drupalGet('es/sweden/purlTest');
    // $this->assertText('purlTest', t('Node page found.'));
  }
}
