<?php

declare(strict_types=1);

namespace ItQuasar\WpHelpers\Ajax;

use function wp_send_json_error;
use function wp_send_json_success;

abstract class AbstractAjaxHandler
{
  public function init()
  {
    add_action('wp_ajax_'.$this->actionName(), function () {$this->process(false); });
    add_action('wp_ajax_nopriv_'.$this->actionName(), function () {$this->process(true); });
  }

  abstract public function actionName(): string;

  /**
   * Обработчик запроса для привелегированных пользователей.
   *
   * @return array
   *
   * @throws AjaxException
   */
  abstract public function callback(): array;

  /**
   * Обработчик запроса для НЕ привелегированных пользователей.
   *
   * По-умолчанию, если не переопределен, то вызовет обработчик запросов для привелегированных пользователей.
   *
   * @return array
   *
   * @throws AjaxException
   */
  public function noPrivilegeCallback(): array
  {
    return $this->callback();
  }

  private function process($isNoPrivilege)
  {
    try {
      $result = $isNoPrivilege ? $this->noPrivilegeCallback() : $this->callback();
      wp_send_json_success($result);
    } catch (AjaxException $exception) {
      wp_send_json_error([
        'code' => $exception->getCode(),
        'message' => $exception->getMessage(),
      ]);
    }
  }
}
