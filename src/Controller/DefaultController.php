<?php /**
 * @file
 * Contains \Drupal\purl\Controller\DefaultController.
 */

namespace Drupal\purl\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Default controller for the purl module.
 */
class DefaultController extends ControllerBase {

  public function purl_admin() {
    global $pager_page_array, $pager_total, $pager_total_items;
    $page = isset($_GET['page']) ? $_GET['page'] : 0;
    $element = 0;
    $limit = 20;
    $providers = purl_providers();

    // Convert $page to an array, used by other functions.
    $pager_page_array = [
      $page
      ];

    $methods = _purl_options();

    $merged = [];
    foreach (array_keys($methods) as $method) {
      foreach (purl_modifiers($method) as $value => $info) {
        $info['value'] = $value;
        $merged[] = $info;
      }
    }

    $rows = [];
    for ($i = $page * $limit; $i < ($page + 1) * $limit && $i < count($merged); $i++) {
      // @FIXME
// // @FIXME
// // The correct configuration object could not be determined. You'll need to
// // rewrite this call manually.
// $rows[] = array(
//       $providers[$merged[$i]['provider']]['name'],
//       $merged[$i]['value'],
//       $merged[$i]['id'],
//       $methods[variable_get('purl_method_' . $merged[$i]['provider'], 'path')],
//     );

    }

    // We calculate the total of pages as ceil(items / limit).
    $pager_total_items[$element] = count($merged);
    $pager_total[$element] = ceil($pager_total_items[$element] / $limit);
    $pager_page_array[$element] = max(0, min((int) $pager_page_array[$element], ((int) $pager_total[$element]) - 1));

    if ($rows) {
      // @FIXME
// theme() has been renamed to _theme() and should NEVER be called directly.
// Calling _theme() directly can alter the expected output and potentially
// introduce security issues (see https://www.drupal.org/node/2195739). You
// should use renderable arrays instead.
// 
// 
// @see https://www.drupal.org/node/2195739
// $output = theme('table', array('header' => array(t('Provider'), t('Modifier'), t('ID'), t('Method')), 'rows' => $rows));

      // @FIXME
// theme() has been renamed to _theme() and should NEVER be called directly.
// Calling _theme() directly can alter the expected output and potentially
// introduce security issues (see https://www.drupal.org/node/2195739). You
// should use renderable arrays instead.
// 
// 
// @see https://www.drupal.org/node/2195739
// $output .= theme('pager');

    }
    else {
      $output = "<p>" . t('No persistent urls have been registered.') . "</p>";
    }
    return $output;
  }

}
