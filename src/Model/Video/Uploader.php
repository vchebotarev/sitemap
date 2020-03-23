<?php

declare(strict_types=1);

namespace Chebur\Sitemap\Model\Video;

use InvalidArgumentException;
use Psr\Http\Message\UriInterface;

class Uploader
{
    /**
     * @var string
     */
    private $uploader;

    /**
     * @var UriInterface|null
     */
    private $info;

    public function __construct(string $value, ?UriInterface $info)
    {
        if (mb_strlen($value) > 255) {
            throw new InvalidArgumentException('Uploader\'s name string length must not be greater then 255');
        }
        $this->uploader = $value;
        $this->info = $info;
    }

    public function getUploader(): string
    {
        return $this->uploader;
    }

    public function getInfo(): ?UriInterface
    {
        return $this->info;
    }
}
