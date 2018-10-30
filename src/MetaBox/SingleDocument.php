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
 * Один документ.
 */
class SingleDocument extends AbstractMetaBoxBaseField
{
  /** @var string Тип PDF */
  public const MIME_PDF = 'application/pdf';
  /** @var string Тип Word */
  public const MIME_WORD = 'application/msword,
   application/vnd.openxmlformats-officedocument.wordprocessingml.document,
   application/vnd.openxmlformats-officedocument.wordprocessingml.template,
   application/vnd.ms-word.document.macroEnabled.12,
   application/vnd.ms-word.template.macroEnabled.12';
  /** @var string Тип Excel */
  public const MIME_EXCEL = 'application/vnd.ms-excel,
   application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,
   application/vnd.openxmlformats-officedocument.spreadsheetml.template,
   application/vnd.ms-excel.sheet.macroEnabled.12,
   application/vnd.ms-excel.template.macroEnabled.12,
   application/vnd.ms-excel.addin.macroEnabled.12,
   application/vnd.ms-excel.sheet.binary.macroEnabled.12';
  /** @var string Тип image */
  public const MIME_IMAGE = 'image';
  /** @var string Тип audio */
  public const MIME_AUDIO = 'audio';
  /** @var string Тип Power Point */
  public const MIME_POWER_POINT = 'application/vnd.ms-powerpoint,
   application/vnd.openxmlformats-officedocument.presentationml.presentation,
   application/vnd.openxmlformats-officedocument.presentationml.template,
   application/vnd.openxmlformats-officedocument.presentationml.slideshow,
   application/vnd.ms-powerpoint.addin.macroEnabled.12,
   application/vnd.ms-powerpoint.presentation.macroEnabled.12,
   application/vnd.ms-powerpoint.template.macroEnabled.12,
   application/vnd.ms-powerpoint.slideshow.macroEnabled.12';
  /** @var string Тип Access */
  public const MIME_ACCESS = 'application/vnd.ms-access';

  /** @var bool */
  private $forceDelete = false;
  /** @var string */
  private $mimeTypes = null;

  /**
   * Устанавливает признак того, что документ должен принудительно удаляться из библиотеки Медиа, если
   * его удалят из MetaBox'a.
   *
   * Будтье внимательны, т.к. документ может использоваться в каком-нибудь другом месте.
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

  /**
   * Устанавливает MIME типы документов, которые можно выбрать из галереи.
   *
   * Внимание, данный параметр не влияет на MIME типы, которые можно выбрать при загрузке файлов,
   * Проверяет только MIME типы, которые можно выбрать из галереи.
   *
   * @param array $mimeTypes
   *
   * @return SingleDocument
   */
  public function setMimeTypes(array $mimeTypes): self
  {
    $this->mimeTypes = implode(', ', $mimeTypes);

    return $this;
  }

  protected function getMetaBoxConfig(): array
  {
    $result = [
      'type' => 'file_advanced',
      'max_file_uploads' => 1,
    ];

    if ($this->mimeTypes) {
      $result['mime_type'] = $this->mimeTypes;
    }

    if ($this->forceDelete) {
      $result['force_delete'] = $this->forceDelete;
    }

    return $result;
  }
}
