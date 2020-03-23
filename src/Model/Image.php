<?php

declare(strict_types=1);

namespace Chebur\Sitemap\Model;

use Psr\Http\Message\UriInterface;

/**
 * https://support.google.com/webmasters/answer/178636
 */
class Image
{
    /**
     * @var UriInterface
     */
    private $location;

    /**
     * @var string|null
     */
    private $caption;

    /**
     * @var string|null
     */
    private $geoLocation;

    /**
     * @var string|null
     */
    private $title;

    /**
     * @var UriInterface|null
     */
    private $license;

    public function __construct(
        UriInterface $location,
        ?string $caption = null,
        ?string $geoLocation = null,
        ?string $title = null,
        ?UriInterface $license = null
    ) {
        $this->location = $location;
        $this->caption = $caption;
        $this->geoLocation = $geoLocation;
        $this->title = $title;
        $this->license = $license;
    }

    public function getLocation(): UriInterface
    {
        return $this->location;
    }

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function getGeoLocation(): ?string
    {
        return $this->geoLocation;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getLicense(): ?UriInterface
    {
        return $this->license;
    }
}
