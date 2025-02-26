<?php

namespace App\Services;

use HTMLPurifier;
use HTMLPurifier_Config;

/**
 * Class SanitizationService
 * @package App\Services
 */
class SanitizationService
{
    /**
     * @param string $string
     * @return string
     */
    public function sanitize(string $string): string
    {
        $string = $this->decode_until_plain($string);
        $config = HTMLPurifier_Config::createDefault();

        $config->set('HTML.Allowed', 'p, br, em, strong, u, span[class], h1, h2, ol, ul, li, span[contenteditable]');
        $config->set('Attr.AllowedClasses', ['mention', 'hashtag', 'badge']);

        $purifier = new HTMLPurifier($config);

        return $purifier->purify($string);
    }

    /**
     * @param string $string
     * @return string
     */
    public function decode_until_plain(string $string): string
    {
        $previous = '';

        while ($previous !== $string) {
            $previous = $string;
            $string = html_entity_decode($string);
        }

        return $string;
    }
}
