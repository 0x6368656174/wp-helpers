<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers\MetaBox;

use function array_merge;
use Exception;
use function var_dump;

abstract class AbstractMetaBox
{
  /** @var string Отображает блок под редактором */
  public const CONTEXT_NORMAL = 'normal';

  /** @var string Отобржает блок ниже секции CONTEXT_NORMAL */
  public const CONTEXT_ADVANCED = 'advanced';

  /** @var string Оторажает блок в правой колонке */
  public const CONTEXT_SIDE = 'side';

  /** @var string Отображает блок сверху формы, перед заголовком */
  public const CONTEXT_FORM_TOP = 'form_top';

  /** @var string Отображает блок под заголовком */
  public const CONTEXT_AFTER_TITLE = 'after_title';

  /** @var string Отображает блок под редактором, но перед CONTEXT_NORMAL */
  public const CONTEXT_AFTER_EDITOR = 'after_editor';

  /** @var string Отображает блок перед постоянной ссылкой */
  public const CONTEXT_BEFORE_PERMALINK = 'before_permalink';

  /** @var string Отображает блок внутри контекста с высшим приоритетом */
  public const PRIORITY_HIGH = 'high';

  /** @var string Отображает блок внутри контекста с низщим приоритетом */
  public const PRIORITY_LOW = 'low';

  /**
   * Возвращает ID MetaBox.
   *
   * @return string
   */
  abstract public static function getId(): string;

  /**
   * Возвращает назваение MetaBox.
   *
   * @return string
   */
  abstract public static function getTitle(): string;

  /**
   * Возвращает массив кофигурации MetaBox.io.
   *
   * @return array
   */
  public static function toMetaBoxArray(): array
  {
    $id = static::getId();

    $fields = [];
    $tabs = [];

    $controls = static::getControls();
    for ($i = 0; $i < count($controls); ++$i) {
      $control = $controls[$i];
      $localFields = [];

      $control->addToMetaBoxFields($id, $localFields);

      if ($control instanceof Tab) {
        $tabId = "tab-$i";
        $tabs [$tabId]= [
          'label' => $control->getName(),
          'icon' => $control->getIcon(),
        ];

        foreach($localFields as &$localField) {
          $localField['tab'] = $tabId;
        }
      }

      $fields = array_merge($fields, $localFields);
    }

    $result = [
      'autosave' => static::getAutosave(),
      'id' => static::getId(),
      'title' => static::getTitle(),
      'context' => static::getContext(),
      'priority' => static::getPriority(),
      'fields' => $fields,
    ];

    if (count($tabs) > 0) {
      $result['tabs'] = $tabs;
    }

    $include = static::getInclude();
    if (null !== $include) {
      $result['include'] = $include;
    }

    $exclude = static::getExclude();
    if (null !== $exclude) {
      $result['exclude'] = $exclude;
    }

    return $result;
  }

  /**
   * Возвращает контролы, используемые в MetaBox.
   *
   * В качестве контролов могут быть возвращены закладки, для группирования контрола в закладки. Для работы закладок
   * необходимо, чтоб был установлен и включен плагин Meta Box Tabs.
   *
   * Для установки плагина при помощи Composer:
   * ```
   * $ composer require meta-box/meta-box-tabs:dev-master
   * ```
   *
   * @return AbstractMetaBoxControl[]
   *
   * @see https://docs.metabox.io/extensions/meta-box-tabs/
   */
  abstract protected static function getControls(): array;

  /**
   * Возвращает контекст, в котором будет отображаться MetaBox.
   *
   * Возможные значения: все константы класса AbstractMetaBox::CONTEXT_*.
   *
   * По-умолчанию,  AbstractMetaBox::CONTEXT_ADVANCED
   *
   * @return string
   */
  protected static function getContext(): string
  {
    return static::CONTEXT_ADVANCED;
  }

  /**
   * Возвращает приоритет, с корором будет отображаться MetaBox внутри контекста.
   *
   * Возможные значения: все константы класса AbstractMetaBox::PRIORITY_*.
   *
   * По-умолчанию,  AbstractMetaBox::PRIORITY_HIGH
   *
   * @return string
   */
  protected static function getPriority(): string
  {
    return static::PRIORITY_HIGH;
  }

  /**
   * Возвращает условие включения данного MetaBox.
   *
   * Для работы условий, используется плагин Meta Box Include Exclude. Плагин должен быть
   * установлен и включен. Возвращаемый массив должен соответствовать описанию плагина.
   *
   * Если возвращает null, то условия включения не используется.
   *
   * Для установки плагина при помощи Composer:
   * ```
   * $ composer require meta-box/meta-box-include-exclude:dev-master
   * ```
   *
   * По-умолчанию, возвращает null.
   *
   * @return array|null
   *
   * @see https://docs.metabox.io/extensions/meta-box-include-exclude/
   */
  protected static function getInclude(): ?array
  {
    return null;
  }

  /**
   * Возвращает условие исключения данного MetaBox.
   *
   * Для работы условий, используется плагин Meta Box Include Exclude. Плагин должен быть
   * установлен и включен. Возвращаемый массив должен соответствовать описанию плагина.
   *
   * Если возвращает null, то условия исключения не используется.
   *
   * Для установки плагина при помощи Composer:
   * ```
   * $ composer require meta-box/meta-box-include-exclude:dev-master
   * ```
   *
   * По-умолчанию, возвращает null.
   *
   * @return array|null
   *
   * @see https://docs.metabox.io/extensions/meta-box-include-exclude/
   */
  protected static function getExclude(): ?array
  {
    return null;
  }

  /**
   * Возвращает признак того, что значение метабокса должно автоматически
   * сохраняться.
   *
   * По-умолчанию, возвращает true.
   *
   * @return bool
   */
  protected static function getAutosave(): bool
  {
    return true;
  }
}
