<?php

declare(strict_types=1);

namespace Chebur\Sitemap\Model;

use InvalidArgumentException;
use UnexpectedValueException;

class Sitemap
{
    /**
     * @var ChildSitemap[]|Page[]
     */
    private $items;

    /**
     * @param ChildSitemap[]|Page[]
     */
    public function __construct(array $items)
    {
        if (empty($items)) {
            throw new UnexpectedValueException('Sitemap items must not be empty');
        }
        $isPage = array_values($items)[0] instanceof Page;
        foreach ($items as $item) {
            if (!($item instanceof Page || $item instanceof ChildSitemap)) {
                throw new InvalidArgumentException('Unknown sitemap item type "'.gettype($item).'"');
            }
            if ($isPage !== $item instanceof Page) {
                throw new InvalidArgumentException('Every sitemap item must be the same type');
            }
        }
        $this->items = $items;
    }

    /**
     * @return ChildSitemap[]|Page[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
