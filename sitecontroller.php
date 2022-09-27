<?php

namespace Main\Controllers;

use Bitrix\Main\Localization\Loc;
use Main\Interfaces\ISite;
use Main\Sites\FirstSite;
use Main\Sites\SecondSite;

Loc::loadMessages(__FILE__);

final class SiteController
{
    private const SITES = [
        's1' => 'firstsite',
        's3' => 'secondsite',
        'ru' => 'firstsite',
        'en' => 'secondsite',
    ];

    private static $instance;

    private ISite $site;

    private function __construct()
    {
        $this->detectSite();
        $this->checkRedirects();
        $this->setLanguageCookies();
    }

    private function __clone()
    {
    }

    private function __wakeup(): void
    {
    }

    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getSiteMapSrc(): string
    {
        return $this->site->getSiteMapSrc();
    }

    public function getRobotsSrc(): string
    {
        return $this->site->getRobotsSrc();
    }

    public function getSiteServerName(?string $lang = null): string
    {
        if ($lang) {
            $site = '\\Main\\Sites\\' . (\ucfirst(self::SITES[$lang]));
            if (class_exists($site)) {
                $site = new $site();

                return $site->getSiteDomain();
            }
        }

        return SITE_SERVER_NAME;
    }

    public function getSiteName(): string
    {
        return $this->site->getSiteName();
    }

    public function getSiteDomain(): string
    {
        return $this->site->getSiteDomain();
    }

    private function detectSite(): void
    {
        $site = '\\Main\\Sites\\' . (\ucfirst(self::SITES[SITE_ID]));
        if (!class_exists($site)) {
            $site = FirstSite::class;
        }
        $this->site = new $site();
    }

    /**
     * @return void
     */
    private function checkRedirects(): void
    {
        $this->site->checkRedirects();
    }

    /**
     * @return void
     * @throws \Bitrix\Main\SystemException
     */
    private function setLanguageCookies(): void
    {
        // set cookies
    }

    public function isFirstSite(): bool
    {
        return $this->site->getSiteDomain() === $this->getFirstSite()->getSiteDomain();
    }

    public function isSecondSite(): bool
    {
        return $this->site->getSiteDomain() === $this->getSecondSite()->getSiteDomain();
    }

    public function replaceContent(string $content)
    {
        return $this->site->replaceContent($content);
    }

    public function getEmail(): string
    {
        return $this->site->getEmail();
    }

    public function getMainLang(): string
    {
        return $this->site->getMainLang();
    }

    public function getChatName(): string
    {
        return $this->site->getChatName();
    }

    public function getFirstSite(): ISite
    {
        return new FirstSite();
    }

    public function getSecondSite(): ISite
    {
        return new SecondSite();
    }

    public function getSiteId(?string $lang = null): string
    {
        if ($lang === 'en') {
            return $this->getFirstSite()->getSiteId();
        }
        if ($lang === 'ru') {
            return $this->getSecondSite()->getSiteId();
        }

        return $this->site->getSiteId();
    }
}
