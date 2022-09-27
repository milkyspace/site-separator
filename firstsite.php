<?php

namespace Main\Sites;

use Bitrix\Main\Localization\Loc;
use Main\Interfaces\ISite;

Loc::loadMessages(__FILE__);

final class FirstSite implements ISite
{
    public const NAME = 'Name';
    private const DOMAIN = 'domain.com';
    private const EMAIL = 'hello@domain.com';
    private const SITE_ID = 's3';
    private const MAIN_LANG = 'en';

    private string $domain;

    public function __construct()
    {
        $this->domain = self::DOMAIN;
    }

    public function getSiteMapSrc(): string
    {
        return 'sitemap-en.xml';
    }

    public function getRobotsSrc(): string
    {
        return 'robotsen.txt';
    }

    public function getSiteName(): string
    {
        return static::NAME;
    }

    public function getSiteDomain(): string
    {
        return $this->domain;
    }

    public function replaceContent(string $content): string
    {
        return str_replace(
            [
                'domain.ru',
                'Yomain.ru',
                'DOMAIN.RU',
                // ...
            ],
            [
                $this->getSiteName(),
                $this->getSiteName(),
                strtoupper($this->getSiteName()),
            ],
            $content
        );
    }

    public function getEmail(): string
    {
        return static::EMAIL;
    }

    public function getMainLang(): string
    {
        return static::MAIN_LANG;
    }

    public function getChatName(): string
    {
        return 'Site.first';
    }

    public function checkRedirects(): void
    {
        // TODO: Implement checkRedirects() method.
    }

    public function getSiteId(): string
    {
        return static::SITE_ID;
    }
}
