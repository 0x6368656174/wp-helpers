<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers\ThemeCustomize;

use Exception;
use WP_Customize_Manager;
use function array_key_exists;
use function get_theme_mod;

abstract class AbstractSection
{
  abstract public static function getId(): string;

  public static function addToCustomize(WP_Customize_Manager $wpCustomize)
  {
    $wpCustomize->add_section(static::getId(), [
      'title' => static::getTitle(),
      'priority' => static::getPriority(),
    ]);

    foreach (static::getCachedControls() as $controlId => $control) {
      $fullControlId = static::getId().'__'.$controlId;
      $wpCustomize->add_setting($fullControlId, [
        'default' => $control->getDefault(),
      ]);
      $wpCustomize->add_control($fullControlId, [
        'section' => static::getId(),
        'label' => $control->getLabel(),
        'type' => $control->getType(),
      ]);
    }
  }

  public static function getControlValue(string $controlId)
  {
    $fullControlId = static::getId().'__'.$controlId;
    $controls = static::getCachedControls();
    if (!array_key_exists($controlId, $controls)) {
      throw new Exception("Control '$controlId' not found");
    }

    $control = $controls[$controlId];

    return get_theme_mod($fullControlId, $control->getDefault());
  }

  abstract protected static function getTitle(): string;

  /**
   * @return Control[]
   */
  abstract protected static function getControls(): array;

  protected static function getPriority(): int
  {
    return 0;
  }

  /**
   * @return Control[]
   */
  private static function getCachedControls(): array
  {
    static $controls = false;
    if (!$controls) {
      $controls = static::getControls();
    }

    return $controls;
  }
}
