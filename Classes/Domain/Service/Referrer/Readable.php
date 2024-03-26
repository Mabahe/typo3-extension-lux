<?php

declare(strict_types=1);
namespace In2code\Lux\Domain\Service\Referrer;

/**
 * Class Readable
 * converts referrers like m.twitter.com to a readable referrer like "Twitter"
 */
class Readable
{
    protected string $referrer = '';

    /**
     * Array with mapping for domains (original => Readable)
     *
     * @var array
     */
    protected array $sources = [
        't.co' => 'X (Twitter)',
        'www.twitter.com' => 'X (Twitter)',
        'm.twitter.com' => 'X (Twitter)',
        'l.twitter.com' => 'X (Twitter)',
        'lm.twitter.com' => 'X (Twitter)',
        'www.facebook.com' => 'Facebook',
        'm.facebook.com' => 'Facebook',
        'l.facebook.com' => 'Facebook',
        'lm.facebook.com' => 'Facebook',
        'www.instagram.com' => 'Instagram',
        'm.instagram.com' => 'Instagram',
        'l.instagram.com' => 'Instagram',
        'lm.instagram.com' => 'Instagram',
        'mobile.instagram.com' => 'Instagram',
        'web.instagram.com' => 'Instagram',
        'lnkd.in' => 'LinkedIn',
        'www.linkedin.com' => 'LinkedIn',
        'm.linkedin.com' => 'LinkedIn',
        'l.linkedin.com' => 'LinkedIn',
        'lm.linkedin.com' => 'LinkedIn',
        'www.google.at' => 'Google Austria',
        'www.google.com' => 'Google International',
        'www.google.ch' => 'Google Switzerland',
        'www.google.de' => 'Google Germany',
        'www.google.fr' => 'Google France',
        'www.google.it' => 'Google Italy',
        'xing.com' => 'Xing',
        'www.xing.com' => 'Xing',
        'www.youtube.com' => 'YouTube',
        'vimeo.com' => 'Vimeo',
        'www.vimeo.com' => 'Vimeo',
        'www.in2code.de' => 'in2code GmbH',
        'cermat.de' => 'Cermat',
        'typo3.org' => 'TYPO3',
        'typo3.com' => 'TYPO3',
        'com.slack' => 'Slack',
        'slack.com' => 'Slack',
    ];

    public function __construct(string $referrer = '')
    {
        $this->referrer = $referrer;
    }

    public function getReadableReferrer(): string
    {
        $domain = $this->getDomain();
        if (array_key_exists($domain, $this->sources)) {
            $domain = $this->sources[$domain];
        }
        return $domain;
    }

    public function getOriginalReferrer(): string
    {
        return $this->referrer;
    }

    protected function getDomain(): string
    {
        $domain = '';
        $parts = parse_url($this->referrer);
        if (array_key_exists('host', $parts)) {
            $domain = $parts['host'];
        }
        return $domain;
    }
}
