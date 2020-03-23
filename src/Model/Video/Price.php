<?php

declare(strict_types=1);

namespace Chebur\Sitemap\Model\Video;

use UnexpectedValueException;

class Price
{
    public const TYPE_RENT = 'rent';
    public const TYPE_OWN = 'own';

    public const RESOLUTION_HD = 'hd';
    public const RESOLUTION_SD = 'sd';

    /**
     * @var float
     */
    private $value;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var string|null
     */
    private $type;

    /**
     * @var string|null
     */
    private $resolution;

    public function __construct(float $value, string $currency, ?string $type, ?string $resolution)
    {
        if ($value <= 0) {
            throw new UnexpectedValueException('Price value must be greater then 0');
        }
        $this->value = $value; //any round precision?

        $this->currency = $currency; //todo try to validate

        if ($type !== null && !in_array($type, [self::TYPE_OWN, self::TYPE_RENT])) {
            throw new UnexpectedValueException('Unknown type value "'.$type.'"');
        }
        $this->type = $type;

        if ($resolution !== null && !in_array($resolution, [self::RESOLUTION_HD, self::RESOLUTION_SD])) {
            throw new UnexpectedValueException('Unknown resolution value "'.$type.'"');
        }
        $this->resolution = $resolution;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getResolution(): ?string
    {
        return $this->resolution;
    }
}
