<?php
class DataObjectCommentoEmbed extends DataObjectDecorator {
	public static $options = array();

	public function ConvertRich(&$content) {
		$matched = preg_match_all('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@i', $content, $matches);
		if($matched) foreach($matches[0] as $url) {
			$embed = oEmbed::get_oembed_from_url($url, false, self::$options);
			if($embed) {
				$content .= " <div class=\"DataObjectCommentoEmbed\">{$embed->html}</div>";
			}
		}
	}
}
