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
 * Секция.
 *
 * Позволяет объеденить поля в секцию с одним заголовком
 */
class Section extends AbstractMetaBoxControl
{
  use WithTitle, WithDescription, WithFields;

  /**
   * Section constructor.
   *
   * @param string $title  Заголовок
   * @param array  $fields Поля
   */
  public function __construct(string $title, array $fields = [])
  {
    $this->title = $title;
    $this->fields = $fields;
  }

  public function addToMetaBoxFields(string $prefix, array &$fields): void
  {
    $heading = [
      'name' => $this->getTitle(),
      'type' => 'heading',
    ];

    if ($this->getDescription()) {
      $heading['description'] = $this->getDescription();
    }

    $fields[] = $heading;

    foreach ($this->getFields() as $childField) {
      $childField->addToMetaBoxFields($prefix, $fields);
    }
  }
}
