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
 * Поле выбора цвета.
 */
class Color extends AbstractMetaBoxBaseField
{
  private $enableAlphaChannel = false;
  private $palletes = [];

  /**
   * Устанавливает возможность выбрать alpha-канал.
   *
   * По-умолчанию, `false`.
   *
   * @param bool $enableAlphaChannel Признак того, что будет возможно выбрать alpha-канал
   *
   * @return self
   */
  public function setEnableAlphaChannel(bool $enableAlphaChannel): self {
    $this->enableAlphaChannel = $enableAlphaChannel;

    return $this;
  }

  /**
   * Устанавлиает палитру.
   *
   * Каждый элемент палитры должен быть цвет в HEX-формате.
   *
   * @param string[] $palletes Элементы палитры, цвета в HEX-формате
   *
   * @return self
   */
  public function setPalletes(array $palletes): self {
    $this->palletes = $palletes;

    return $this;
  }

  protected function getMetaBoxConfig(): array
  {
    $result = [
      'type' => 'color',
    ];

    if ($this->enableAlphaChannel) {
      $result['alpha_channel'] = true;
    }

    if (count($this->palletes) > 0) {
      $result['js_options'] = [
        'palettes' => $this->palletes,
      ];
    }

    return $result;
  }
}
