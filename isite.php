<?php

namespace Main\Interfaces;

/**
 * Interface ISite
 * @package Main\Interfaces
 */
interface ISite
{
    public function getSiteDomain(): string;
    public function getSiteName(): string;
    public function getSiteMapSrc(): string;
    public function getRobotsSrc(): string;
    public function replaceContent(string $content): string;
    public function getEmail(): string;
    public function getMainLang(): string;
    public function getChatName(): string;
    public function checkRedirects(): void;
    public function getSiteId(): string;
}
