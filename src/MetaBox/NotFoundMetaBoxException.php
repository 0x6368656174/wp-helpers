<?php

namespace ItQuasar\WpHelpers\MetaBox;

use Exception;

/**
 * Не найден MetaBox c указанным ID
 */
class NotFoundMetaBoxException extends Exception {
  private $id;

  /**
   * @param string $id ID MetaBox'a
   */
  public function __construct(string $id ) {
    parent::__construct("Not found MetaBox control with id = $id");
    $this->id = $id;
  }

  /**
   * Возвращает ID MetaBox'a
   *
   * @return string
   */
  public function getId(): string
  {
    return $this->id;
  }
}
