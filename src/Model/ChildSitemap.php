<?php

declare(strict_types=1);

namespace Chebur\Sitemap\Model;

use DateTimeInterface;
use Psr\Http\Message\UriInterface;

class ChildSitemap
{
    /**
     * @var UriInterface
     */
    private $location;

    /**
     * @var DateTimeInterface|null
     */
    private $lastModified;

    public function __construct(UriInterface $location, ?DateTimeInterface $lastModified)
    {
        $this->location = $location;
        $this->lastModified = $lastModified;
    }

    public function getLocation(): UriInterface
    {
        return $this->location;
    }

    public function getLastModified(): ?DateTimeInterface
    {
        return $this->lastModified;
    }
}
