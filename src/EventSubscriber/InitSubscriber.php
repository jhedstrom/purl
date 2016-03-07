<?php /**
 * @file
 * Contains \Drupal\purl\EventSubscriber\InitSubscriber.
 */

namespace Drupal\purl\EventSubscriber;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class InitSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [KernelEvents::REQUEST => ['onEvent', 0]];
  }

  public function onEvent() {
    static $once;
    if (!isset($once)) {
      $once = TRUE;
      // Initialize a few things so that we can use them without warnings.
      if (!isset($_GET['q'])) {
        $_GET['q'] = '';
      }

      // Initialize the PURL path modification stack.
      purl_inited(TRUE);

      // TODO - is this still needed?? (Does language_init still modify $_GET['q'])
      // $_GET['q'] = $q = purl_language_strip($_REQUEST['q']);
      $_GET['q'] = $q = $_GET['q'];
      drupal_path_initialize();
      purl_get_normal_path($q, TRUE);
    }
  }

}
