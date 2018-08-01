<?php


namespace ItQuasar\WpHelpers;


use WP_Embed;

class Embed {
  public static function getYouTubeVideoPlayer(string $videoUrl, int $width, int $height): string {
    $embed = new WP_Embed();

    return $embed->run_shortcode('[embed width="' . $width . '" height="' . $height . '"]' . $videoUrl . '[/embed]');
  }

}
