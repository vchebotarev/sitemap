<?php

declare(strict_types=1);

namespace Chebur\Sitemap\Model;

use Chebur\Sitemap\Model\Video\Platform;
use Chebur\Sitemap\Model\Video\PlayerLocation;
use Chebur\Sitemap\Model\Video\Price;
use Chebur\Sitemap\Model\Video\Restriction;
use Chebur\Sitemap\Model\Video\Uploader;
use DateTimeInterface;
use InvalidArgumentException;
use Psr\Http\Message\UriInterface;
use UnexpectedValueException;

/**
 * https://support.google.com/webmasters/answer/80471
 */
class Video
{
    /**
     * @var UriInterface
     */
    private $thumbnailLocation;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var UriInterface|null
     */
    private $contentLocation;

    /**
     * @var PlayerLocation|null
     */
    private $playerLocation;

    /**
     * @var int|null
     */
    private $duration;

    /**
     * @var DateTimeInterface|null
     */
    private $expirationDate;

    /**
     * @var float|null
     */
    private $rating;

    /**
     * @var int|null
     */
    private $viewCount;

    /**
     * @var DateTimeInterface|null
     */
    private $publicationDate;

    /**
     * @var bool|null
     */
    private $isFamilyFriendly;

    /**
     * @var Restriction|null
     */
    private $restriction;

    /**
     * @var Platform|null
     */
    private $platform;

    /**
     * @var Price[]
     */
    private $prices = [];

    /**
     * @var bool|null
     */
    private $isRequiresSubscription;

    /**
     * @var Uploader|null
     */
    private $uploader;

    /**
     * @var bool|null
     */
    private $isLive;

    /**
     * @var string[]
     */
    private $tags;

    /**
     * @var string|null
     */
    private $category;

    /**
     * @param Price[] $prices
     * @param string[] $tags
     */
    public function __construct(
        UriInterface $thumbnailLocation,
        string $title,
        string $description,
        ?UriInterface $contentLocation = null,
        ?PlayerLocation $playerLocation = null,
        ?int $duration = null,
        ?DateTimeInterface $expirationDate = null,
        ?float $rating = null,
        ?int $viewCount = null,
        ?DateTimeInterface $publicationDate = null,
        ?bool $isFamilyFriendly = null,
        ?Restriction $restriction = null,
        ?Platform $platform = null,
        array $prices = [],
        ?bool $isRequiresSubscription = null,
        ?Uploader $uploader = null,
        ?bool $isLive = null,
        array $tags = [],
        ?string $category = null
    ) {
        $this->thumbnailLocation = $thumbnailLocation;

        $this->title = $title;

        if (mb_strlen($description) > 2048) {
            throw new UnexpectedValueException('Description string length must not be greater then 2048');
        }
        $this->description = $description;

        if (!($contentLocation === null xor $playerLocation === null)) {
            throw new InvalidArgumentException('Player location or content location must be set');
        }

        $this->contentLocation = $contentLocation;
        $this->playerLocation = $playerLocation;

        if ($duration !== null && ($duration < 1 || $duration > 28800)) {
            throw new UnexpectedValueException('Duration value must be between 0 and 28800 seconds');
        }
        $this->duration = $duration;

        $this->expirationDate = $expirationDate;

        if ($rating !== null && ($rating < 0 || $rating > 5)) {
            throw new UnexpectedValueException('Rating value must be between 0 and 5');
        }
        $this->rating = $rating;

        if ($viewCount !== null && $viewCount < 0) {
            throw new UnexpectedValueException('Views count can not be less then 0');
        }
        $this->viewCount = $viewCount;

        $this->publicationDate = $publicationDate;

        $this->isFamilyFriendly = $isFamilyFriendly;

        $this->restriction = $restriction;

        $this->platform = $platform;

        array_walk($prices, function($item){
           if (!$item instanceof Price) {
               throw new InvalidArgumentException('Every price must be instance of "'.Price::class.'" got "'.gettype($item).'"');
           }
        });
        $this->prices = $prices;

        $this->isRequiresSubscription = $isRequiresSubscription;

        $this->uploader = $uploader;

        $this->isLive = $isLive;

        if (count($tags) > 32) {
            throw new UnexpectedValueException('Tags count must not be greater then 32');
        }
        array_walk($tags, function($item){
            if (!is_string($item)) {
                throw new InvalidArgumentException('Every tag must be string got "'.gettype($item).'"');
            }
        });
        $this->tags = $tags;

        if ($category !== null && mb_strlen($category) > 256) {
            throw new UnexpectedValueException('Category string length must not be greater then 256');
        }
        $this->category = $category;
    }

    public function getThumbnailLocation(): UriInterface
    {
        return $this->thumbnailLocation;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getContentLocation(): ?UriInterface
    {
        return $this->contentLocation;
    }

    public function getPlayerLocation(): ?PlayerLocation
    {
        return $this->playerLocation;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function getExpirationDate(): ?DateTimeInterface
    {
        return $this->expirationDate;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function getViewCount(): ?int
    {
        return $this->viewCount;
    }

    public function getPublicationDate(): ?DateTimeInterface
    {
        return $this->publicationDate;
    }

    public function isFamilyFriendly(): ?bool
    {
        return $this->isFamilyFriendly;
    }

    public function getRestriction(): ?Restriction
    {
        return $this->restriction;
    }

    public function getPlatform(): ?Platform
    {
        return $this->platform;
    }

    /**
     * @return Price[]
     */
    public function getPrices(): array
    {
        return $this->prices;
    }

    public function isRequiresSubscription(): ?bool
    {
        return $this->isRequiresSubscription;
    }

    public function getUploader(): ?Uploader
    {
        return $this->uploader;
    }

    public function isLive(): ?bool
    {
        return $this->isLive;
    }

    /**
     * @return string[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }
}
