<?php

declare(strict_types=1);

namespace Chebur\Sitemap\Model;

use DateTimeInterface;
use InvalidArgumentException;
use Psr\Http\Message\UriInterface;
use UnexpectedValueException;

/**
 * https://www.sitemaps.org/protocol.html
 */
class Page
{
    public const CHANGE_FREQUENCE_ALWAYS = 'always';
    public const CHANGE_FREQUENCE_HOURLY = 'hourly';
    public const CHANGE_FREQUENCE_DAILY = 'daily';
    public const CHANGE_FREQUENCE_WEEKLY = 'weekly';
    public const CHANGE_FREQUENCE_MONTHLY = 'monthly';
    public const CHANGE_FREQUENCE_YEARLY = 'yearly';
    public const CHANGE_FREQUENCE_NEVER = 'never';

    /**
     * @var UriInterface
     */
    private $location;

    /**
     * @var DateTimeInterface|null
     */
    private $lastModified;

    /**
     * @var string|null
     */
    private $changeFrequence;

    /**
     * @var float|null
     */
    private $priority;

    /**
     * @var Image[]
     */
    private $images;

    /**
     * @var Video[]
     */
    private $videos;

    public function __construct(
        UriInterface $location,
        ?DateTimeInterface $lastModified = null,
        ?string $changeFrequence = null,
        ?float $priority = null,
        array $images = [],
        array $videos = []
    ) {
        $this->location = $location;
        $this->lastModified = $lastModified;

        $availableChangeFrequence = [
            self::CHANGE_FREQUENCE_ALWAYS,
            self::CHANGE_FREQUENCE_HOURLY,
            self::CHANGE_FREQUENCE_DAILY,
            self::CHANGE_FREQUENCE_WEEKLY,
            self::CHANGE_FREQUENCE_MONTHLY,
            self::CHANGE_FREQUENCE_YEARLY,
            self::CHANGE_FREQUENCE_NEVER,
        ];
        if ($changeFrequence !== null && !in_array($changeFrequence, $availableChangeFrequence, true)) {
            throw new UnexpectedValueException('');
        }
        $this->changeFrequence = $changeFrequence;

        if ($priority !== null && ($priority < 0 || $priority > 5)) {
            throw new UnexpectedValueException('Priority value must be between 0 and 5, got "'.$priority.'"');
        }
        $this->priority = $priority;

        array_walk($images, function ($item) {
            if (!$item instanceof Image) {
                throw new InvalidArgumentException('Every image must be instance of '.Image::class.' got '.gettype($item));
            }
        });
        $this->images = $images;

        array_walk($videos, function ($item) use ($location) {
            if (!$item instanceof Video) {
                throw new InvalidArgumentException('Every video must be instance of '.Video::class.' got '.gettype($item));
            }
            if ($item->getContentLocation() !== null && $item->getContentLocation()->__toString() === $location->__toString()) {
                throw new UnexpectedValueException('Video content location must not be equal to url location');
            }
            if ($item->getPlayerLocation() !== null && $item->getPlayerLocation()->getLocation()->__toString() === $location->__toString()) {
                throw new UnexpectedValueException('Video player location must not be equal to url location');
            }
            if ($item->getUploader() !== null && $item->getUploader()->getInfo() !== null && $item->getUploader()->getInfo()->getHost() !== $location->getHost()) {
                throw new UnexpectedValueException('Uploader info url must be in the same domain as page url');
            }
        });
        $this->videos = $videos;
    }

    public function getLocation(): UriInterface
    {
        return $this->location;
    }

    public function getLastModified(): ?DateTimeInterface
    {
        return $this->lastModified;
    }

    public function getChangeFrequence(): ?string
    {
        return $this->changeFrequence;
    }

    public function getPriority(): ?float
    {
        return $this->priority;
    }

    /**
     * @return Image[]
     */
    public function getImages(): array
    {
        return $this->images;
    }

    /**
     * @return Video[]
     */
    public function getVideos(): array
    {
        return $this->videos;
    }
}
