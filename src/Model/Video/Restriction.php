<?php

declare(strict_types=1);

namespace Chebur\Sitemap\Model\Video;

use InvalidArgumentException;

class Restriction
{
    /**
     * @var string[]
     */
    private $countries;

    /**
     * @var bool
     */
    private $isAllowed;

    public function __construct(array $countries, bool $isAllowed)
    {
        if(empty($countries)) {
            throw new InvalidArgumentException('Countries list must not be empty');
        }
        array_walk($countries, function ($item){
            if (!is_string($item)) {
                throw new InvalidArgumentException('Invalid countries item type expected "string" got "'.gettype($item).'"');
            }
        });
        $this->countries = $countries; //todo try to validate
        $this->isAllowed = $isAllowed;
    }

    /**
     * @return string[]
     */
    public function getCountries(): array
    {
        return $this->countries;
    }

    public function isAllowed(): bool
    {
        return $this->isAllowed;
    }
}
