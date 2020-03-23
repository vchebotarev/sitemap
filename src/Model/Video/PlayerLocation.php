<?php

declare(strict_types=1);

namespace Chebur\Sitemap\Model\Video;

use Psr\Http\Message\UriInterface;

class PlayerLocation
{
    /**
     * @var UriInterface
     */
    private $location;

    /**
     * @var string|null
     */
    private $isAllowedEmbed;

    public function __construct(UriInterface $location, ?bool $allowEmbed)
    {
        $this->location = $location;
        $this->isAllowedEmbed = $allowEmbed;
    }

    public function getLocation(): UriInterface
    {
        return $this->location;
    }

    public function isAllowedEmbed(): ?bool
    {
        return $this->isAllowedEmbed;
    }
}
