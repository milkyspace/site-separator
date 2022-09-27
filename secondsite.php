<?php

namespace Main\Sites;

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\SiteTable;
use Bitrix\Main\Web\Uri;
use Main\Interfaces\ISite;

Loc::loadMessages(__FILE__);

final class SecondSite implements ISite
{
    public const NAME = 'Name';
    private const DOMAIN = 'domain.ru';
    private const EMAIL = 'hello@domain.ru';
    private const SITE_ID = 's1';
    private const MAIN_LANG = 'ru';

    private string $domain;

    public function __construct()
    {
        $this->domain = self::DOMAIN;
    }

    public function getSiteMapSrc(): string
    {
        return 'sitemap-ru.xml';
    }

    public function getRobotsSrc(): string
    {
        return 'robotsru.txt';
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
        return $content;
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
        return 'Site.second';
    }

    public function checkRedirects(): void
    {
        $request = Application::getInstance()->getContext()->getRequest();

        if ($request->isAdminSection()) {
            return;
        }

        // process of data for redirects
        $uri = new Uri($request->getRequestUri());

        $getLang = $request->getQuery('lang');

        $pathRequest = '';
        $langFromUri = 'ru';
        if (preg_match('#/(en|ru)/#', $uri->getUri(), $aMatches) || in_array($getLang, ['ru', 'en'])) {
            $langFromUri = $aMatches[1];
            $uri->deleteParams(['lang']);
            $pathRequest = str_replace(['en/'], [''], $uri->getPathQuery());
        }

        if (in_array('en', [$getLang, $langFromUri], true)) {
            $domain = SiteTable::getList([
                'select' => [
                    'SERVER_NAME',
                ],
                'filter' => [
                    'LANGUAGE_ID' => 'en',
                ],
            ])->fetch()['SERVER_NAME'];
            header('HTTP/1.1 301 Moved Permanently');
            header("Location: https://{$domain}" . $pathRequest);
            exit();
        }
    }

    public function getSiteId(): string
    {
        return static::SITE_ID;
    }
}
