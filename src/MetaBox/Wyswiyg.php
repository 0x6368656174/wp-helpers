<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers\MetaBox;

/**
 * Расширенный редактор WYSWIYG
 */
class Wyswiyg extends AbstractMetaBoxBaseField
{
  /** @var string  */
  private $class = 'rwmb-wysiwyg';

  /**
   * Устанавлиает класс (CSS класс) редактора.
   *
   * По-умолчанию, 'rwmb-wysiwyg'.
   *
   * @param string $class Класс
   *
   * @return self
   */
  public function setClass(string $class): self
  {
    $this->class = $class;

    return $this;
  }

  protected function getMetaBoxConfig(): array
  {
    $result = [
      'type' => 'wysiwyg',
    ];

    if ('rwmb-wysiwyg' !== $this->class) {
      $result['options'] = [
        'editor_class' => $this->class,
      ];
    }

    return $result;
  }
}
