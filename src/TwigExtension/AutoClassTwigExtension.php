<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers\TwigExtension;

use DOMDocument;
use DOMElement;
use RecursiveIteratorIterator;
use function strtolower;
use Twig_Extension;
use Twig_Filter;

class AutoClassTwigExtension extends Twig_Extension
{
  public function getFilters()
  {
    return [
      new Twig_Filter('autoClass', [$this, 'getAutoClassString']),
    ];
  }

  public function getAutoClassString(string $string, string $prefix)
  {
    $document = new DOMDocument();
    $document->loadHTML('<?xml encoding="utf-8" ?>' . $string);

    $dit = new RecursiveIteratorIterator(
      new RecursiveDOMIterator($document),
      RecursiveIteratorIterator::SELF_FIRST);

    foreach($dit as $node) {
      if($node instanceof DOMElement) {
        $node->setAttribute('class', $this->getClassName($node, $prefix));
      }
    }

    return $document->saveHTML();
  }

  private function getClassName(DomElement $element, string $prefix): string {
    $class = $element->getAttribute('class');

    $lower = strtolower($element->nodeName);

    switch ($lower) {
      case 'p': return $class.' '.$prefix.$lower.' '.$prefix.'paragraph';
      case 'ul': return $class.' '.$prefix.$lower.' '.$prefix.'mark-list'.' '.$prefix.'mark-list--level-'.$this->childLevel($element, 'ul');
      case 'ol': return $class.' '.$prefix.$lower.' '.$prefix.'number-list'.' '.$prefix.'number-list--level-'.$this->childLevel($element, 'ol');
      case 'li': return $class.' '.$prefix.$lower.' '.$prefix.'list-item'.' '.$prefix.'list-item--level-'.$this->childLevel($element, 'li');
      case 'tbody': return $class.' '.$prefix.$lower.' '.$prefix.'table-body';
      case 'tr': return $class.' '.$prefix.$lower.' '.$prefix.'table-row';
      case 'td': return $class.' '.$prefix.$lower.' '.$prefix.'table-cell';
      case 'strong': return $class.' '.$prefix.$lower.' '.$prefix.'text--strong';
      default: return $class.' '.$prefix.$lower;
    }
  }

  private function childLevel(DOMElement $child, string $nodeName): int {
    $level = 1;
    $parent = $child->parentNode;
    while ($parent) {
      if (strtolower($parent->nodeName) === $nodeName) {
        $level++;
      }
      $parent = $parent->parentNode;
    }

    return $level;
  }
}
