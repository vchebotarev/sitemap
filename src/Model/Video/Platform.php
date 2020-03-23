<?php

declare(strict_types=1);

namespace Chebur\Sitemap\Model\Video;

use InvalidArgumentException;
use UnexpectedValueException;

class Platform
{
    public const WEB = 'web';
    public const MOBILE = 'mobile';
    public const TV = 'tv';

    /**
     * @var string[]
     */
    private $platforms;

    /**
     * @var bool
     */
    private $isAllowed;

    public function __construct(array $platforms, string $relationship)
    {
        if (empty($platforms)) {
            throw new UnexpectedValueException('Platform list must not be empty');
        }
        foreach ($platforms as $platform) {
            if (!is_string($platform)) {
                throw new InvalidArgumentException('Every platform item must be string got '.gettype($platform));
            }
            if (!in_array($platform, [self::WEB, self::MOBILE, self::TV], true)) {
                throw new UnexpectedValueException('Unknown platform value "'.$platform.'"');
            }
        }
        $this->platforms = $platforms;
        $this->isAllowed = $relationship;
    }

    /**
     * @return string[]
     */
    public function getPlatforms(): array
    {
        return $this->platforms;
    }

    public function isAllowed(): bool
    {
        return $this->isAllowed;
    }
}
