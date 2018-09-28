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
 * Одна картинка.
 */
class SingleImage extends AbstractMetaBoxBaseField
{
  /** @var bool */
  private $forceDelete = false;

  /**
   * Устанавливает признак того, что изображение должно принудительно удаляться из библиотеки Медиа, если
   * его удалят из MetaBox'a.
   *
   * Будтье внимательны, т.к. изображение может использоваться в каком-нибудь другом месте.
   *
   * По-умолчанию, `false`.
   *
   * @param bool $forceDelete
   *
   * @return self
   */
  public function setForceDelete(bool $forceDelete): self
  {
    $this->forceDelete = $forceDelete;

    return $this;
  }

  protected function getMetaBoxConfig(): array
  {
    $result = [
      'type' => 'single_image',
    ];

    if ($this->forceDelete) {
      $result['force_delete'] = true;
    }

    return $result;
  }
}
