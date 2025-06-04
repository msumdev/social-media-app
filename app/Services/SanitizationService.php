<?php

namespace App\Services;

use HTMLPurifier;
use HTMLPurifier_Config;

/**
 * Class SanitizationService
 */
class SanitizationService
{
    public function sanitize(string $string): string
    {
        $string = $this->decode_until_plain($string);
        $config = HTMLPurifier_Config::createDefault();

        $config->set('HTML.Allowed', 'p, br, em, strong, u, span[class], h1, h2, ol, ul, li, span[contenteditable]');
        $config->set('Attr.AllowedClasses', [
            'bg-green-50',
            'text-green-600',
            'bg-blue-50',
            'text-blue-400',
            'me-1',
            'px-1.5',
            'py-0.5',
            'rounded-sm',
            'dark:bg-blue-900',
            'dark:text-blue-300'
        ]);

        $purifier = new HTMLPurifier($config);

        return $purifier->purify($string);
    }

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
