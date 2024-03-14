<?php

declare(strict_types=1);
namespace In2code\Lux\Domain\Service\Referrer;

/**
 * Class SocialMedia
 * get social media sources by domains. This is needed because some SM platforms are using more then simply one domain
 */
class SocialMedia
{
    /**
     * Array with mapping for domains (original => Readable)
     *
     * @var array
     */
    protected array $sources = [
        [
            'name' => 'Facebook',
            'domains' => [
                'www.facebook.com',
                'm.facebook.com',
                'l.facebook.com',
                'lm.facebook.com',
            ],
        ],
        [
            'name' => 'Instagram',
            'domains' => [
                'instagram.com',
                'www.instagram.com',
                'm.instagram.com',
                'l.instagram.com',
                'lm.instagram.com',
                'mobile.instagram.com',
                'web.instagram.com',
            ],
        ],
        [
            'name' => 'LinkedIn',
            'domains' => [
                'lnkd.in',
                'www.linkedin.com',
                'm.linkedin.com',
                'l.linkedin.com',
                'lm.linkedin.com',
            ],
        ],
        [
            'name' => 'X (Twitter)',
            'domains' => [
                't.co',
                'www.twitter.com',
                'm.twitter.com',
                'l.twitter.com',
                'lm.twitter.com',
            ],
        ],
        [
            'name' => 'Xing',
            'domains' => [
                'xing.com',
            ],
        ],
        [
            'name' => 'YouTube',
            'domains' => [
                'www.youtube.com',
            ],
        ],
        [
            'name' => 'Vimeo',
            'domains' => [
                'vimeo.com',
            ],
        ],
        [
            'name' => 'Reddit',
            'domains' => [
                'www.reddit.com',
                'reddit.com',
                'old.reddit.com',
                'm.reddit.com',
            ],
        ],
        [
            'name' => 'Pinterest',
            'domains' => [
                'www.pinterest.com',
                'pinterest.com',
                'm.pinterest.com',
            ],
        ],
        [
            'name' => 'Tumblr',
            'domains' => [
                'www.tumblr.com',
                'tumblr.com',
                'm.tumblr.com',
            ],
        ],
        [
            'name' => 'TikTok',
            'domains' => [
                'www.tiktok.com',
                'm.tiktok.com',
                'tiktok.com',
            ],
        ],
        [
            'name' => 'Mastodon',
            'domains' => [
                'mastodon.social',
            ],
        ],
        [
            'name' => 'Threads',
            'domains' => [
                'www.threads.net',
                'l.threads.net',
            ],
        ],
    ];

    /**
     * Return example:
     *  [
     *      'Facebook' => 'www.facebook.com|m.facebook.com|l.facebook.com',
     *      'YouTube' => 'www.youtube.com'
     *  ]
     *
     * @return array
     */
    public function getDomainsForQuery(): array
    {
        $domains = [];
        foreach ($this->sources as $socialMedia) {
            $domains[$socialMedia['name']] = implode('|', $socialMedia['domains']);
        }
        return $domains;
    }

    /**
     * Get a query string (split with pipe) with all domains
     *
     * @return string
     */
    public function getAllDomainsForQuery(): string
    {
        $domains = [];
        foreach ($this->sources as $socialMedia) {
            $domains = array_merge($domains, $socialMedia['domains']);
        }
        return implode('|', $domains);
    }
}
