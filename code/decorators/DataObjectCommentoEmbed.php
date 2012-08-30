<?php
class DataObjectCommentoEmbed extends DataExtension {
	public static $options = array();
	public static $embedoptions = array();

	public function ConvertRich(&$content) {
		$matched = preg_match_all('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@i', $content, $matches);
		if($matched) {
			$matches[0] = array_unique($matches[0]);
			if(isset(self::$options["limitPerComment"])) {
				$matches[0] = array_slice($matches[0], 0, (int) self::$options["limitPerComment"]);
			}
			foreach($matches[0] as $url) {
				$embed = oEmbed::get_oembed_from_url($url, false, self::$embedoptions);
				if($embed) {
					$content .= " <div class=\"DataObjectCommentoEmbed\">{$embed->html}</div>";
				}
			}
		}
	}
}
