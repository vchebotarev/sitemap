<?php

declare(strict_types=1);

namespace Chebur\Sitemap;

use Chebur\Sitemap\Model\ChildSitemap;
use Chebur\Sitemap\Model\Image;
use Chebur\Sitemap\Model\Page;
use Chebur\Sitemap\Model\Sitemap;
use Chebur\Sitemap\Model\Video;
use DateTimeInterface;
use SimpleXMLElement;

class XmlGenerator
{
    private const XML_NAMESPACE_IMAGE = 'http://www.google.com/schemas/sitemap-image/1.1';
    private const XML_NAMESPACE_VIDEO = 'http://www.google.com/schemas/sitemap-video/1.1';

    public function generate(Sitemap $sitemap): SimpleXMLElement
    {
        $xml = null;
        $firstItem = array_values($sitemap->getItems())[0];
        if ($firstItem instanceof ChildSitemap) {
            $xml = new SimpleXMLElement(
                '<?xml version="1.0" encoding="UTF-8"?>'
                . '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"/>'
            );
            foreach ($sitemap->getItems() as $childSitemap) {
                $this->generateForChildSitemap($childSitemap, $xml);
            }
        }
        if ($firstItem instanceof Page) {
            $hasImages = false;
            foreach ($sitemap->getItems() as $page) {
                if (!empty($page->getImages())) {
                    $hasImages = true;
                    break;
                }
            }
            $hasVideos = false;
            foreach ($sitemap->getItems() as $page) {
                if (!empty($page->getVideos())) {
                    $hasVideos = true;
                    break;
                }
            }
            $xml = new SimpleXMLElement(
                '<?xml version="1.0" encoding="UTF-8"?>'
                . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"'
                . ($hasImages ? ' xmlns:image="'.self::XML_NAMESPACE_IMAGE.'"' : '')
                . ($hasVideos ? ' xmlns:video="'.self::XML_NAMESPACE_VIDEO.'"' : '')
                . '/>'
            );

            foreach ($sitemap->getItems() as $page) {
                $this->generateForPage($page, $xml);
            }
        }

        return $xml;
    }

    private function generateForPage(Page $page, SimpleXMLElement $xmlUrlset): SimpleXMLElement
    {
        $xmlUrl = $xmlUrlset->addChild('url');
        $xmlUrl->addChild('loc', $page->getLocation()->__toString());
        if ($page->getLastModified() !== null) {
            $xmlUrl->addChild('lastmod', $page->getLastModified()->format(DateTimeInterface::W3C));
        }
        if ($page->getChangeFrequence() !== null) {
            $xmlUrl->addChild('changefreq', $page->getChangeFrequence());
        }
        if ($page->getPriority() !== null) {
            $xmlUrl->addChild('priority', $page->getPriority());
        }

        foreach ($page->getImages() as $image) {
            $this->generateForImage($image, $xmlUrl);
        }
        foreach ($page->getVideos() as $video) {
            $this->generateForVideo($video, $xmlUrl);
        }
        return $xmlUrl;
    }

    private function generateForVideo(Video $video, SimpleXMLElement $xmlUrl): SimpleXMLElement
    {
        $yesNo = function(bool $flag): string {
            return $flag === true ? 'yes' : 'no';
        };
        $allowDeny = function(bool $flag): string {
            return $flag === true ? 'allow' : 'deny';
        };

        $xmlVideo = $xmlUrl->addChild('video:video', null, self::XML_NAMESPACE_VIDEO);

        $xmlVideo->addChild('video:thumbnail_loc', $video->getThumbnailLocation()->__toString());
        $xmlVideo->addChild('video:title', $video->getTitle());
        $xmlVideo->addChild('video:description', $video->getDescription());

        if ($video->getContentLocation() !== null) {
            $xmlVideo->addChild('video:content_loc', $video->getContentLocation()->__toString());
        }
        if ($video->getPlayerLocation() !== null) {
            $xmlElement = $xmlVideo->addChild('video:player_loc', $video->getPlayerLocation()->getLocation()->__toString());
            if ($video->getPlayerLocation()->isAllowedEmbed() !== null) {
                $xmlElement->addAttribute('allow_embed', $yesNo($video->getPlayerLocation()->isAllowedEmbed()));
            }
        }
        if ($video->getDuration() !== null) {
            $xmlVideo->addChild('video:duration', (string) $video->getDuration());
        }
        if ($video->getExpirationDate() !== null) {
            $xmlVideo->addChild('video:expiration_date', $video->getExpirationDate()->format(DateTimeInterface::W3C));
        }
        if ($video->getRating() !== null) {
            $xmlVideo->addChild('video:rating', (string) $video->getRating());
        }
        if ($video->getViewCount() !== null) {
            $xmlVideo->addChild('video:view_count', (string) $video->getViewCount());
        }
        if ($video->getPublicationDate() !== null) {
            $xmlVideo->addChild('video:publication_date', $video->getPublicationDate()->format(DateTimeInterface::W3C));
        }
        if ($video->isFamilyFriendly() !== null) {
            $xmlVideo->addChild('video:family_friendly', $yesNo($video->isFamilyFriendly()));
        }
        if ($video->getRestriction() !== null) {
            $xmlElement = $xmlVideo->addChild('video:restriction', implode(' ', $video->getRestriction()->getCountries()));
            $xmlElement->addAttribute('relationship', $allowDeny($video->getRestriction()->isAllowed()));
        }
        if ($video->getPlatform() !== null) {
            $xmlElement = $xmlVideo->addChild('video:platform', implode(' ', $video->getPlatform()->getPlatforms()));
            $xmlElement->addAttribute('relationship', $allowDeny($video->getPlatform()->isAllowed()));
        }
        foreach ($video->getPrices() as $price) {
            $xmlElement = $xmlVideo->addChild('video:price', (string) $price->getValue());
            $xmlElement->addAttribute('currency', $price->getCurrency());
            if ($price->getType() !== null) {
                $xmlElement->addAttribute('type', $price->getType());
            }
            if ($price->getResolution() !== null) {
                $xmlElement->addAttribute('resolution', $price->getResolution());
            }
        }
        if ($video->isRequiresSubscription() !== null) {
            $xmlVideo->addChild('video:requires_subscription', $yesNo($video->isRequiresSubscription()));
        }
        if ($video->getUploader() !== null) {
            $xmlElement = $xmlVideo->addChild('video:uploader', $video->getUploader()->getUploader());
            if ($video->getUploader()->getInfo() !== null) {
                $xmlElement->addAttribute('info', $video->getUploader()->getInfo());
            }
        }
        if ($video->isLive() !== null) {
            $xmlVideo->addChild('video:live', $yesNo($video->isLive()));
        }
        foreach ($video->getTags() as $tag) {
            $xmlVideo->addChild('video:tag', $tag);
        }
        if ($video->getCategory() !== null) {
            $xmlVideo->addChild('video:category', $video->getCategory());
        }
        return $xmlVideo;
    }

    private function generateForImage(Image $image, SimpleXMLElement $xmlUrl): SimpleXMLElement
    {
        $xmlImage = $xmlUrl->addChild('image:image', null, self::XML_NAMESPACE_IMAGE);
        $xmlImage->addChild('image:loc', $image->getLocation()->__toString());
        if ($image->getCaption() !== null) {
            $xmlImage->addChild('image:caption', $image->getCaption());
        }
        if ($image->getGeoLocation() !== null) {
            $xmlImage->addChild('image:geo_location', $image->getGeoLocation());
        }
        if ($image->getTitle() !== null) {
            $xmlImage->addChild('image:title', $image->getTitle());
        }
        if ($image->getLicense() !== null) {
            $xmlImage->addChild('image:license', $image->getLicense()->__toString());
        }
        return $xmlImage;
    }

    private function generateForChildSitemap(ChildSitemap $childSitemap, SimpleXMLElement $xmlSitemapindex): SimpleXMLElement
    {
        $xmlSitemap = $xmlSitemapindex->addChild('sitemap', null);
        $xmlSitemap->addChild('loc', $childSitemap->getLocation()->__toString());
        if ($childSitemap->getLastModified() !== null) {
            $xmlSitemap->addChild('lastmod', $childSitemap->getLastModified()->format(DateTimeInterface::W3C));
        }
        return $xmlSitemap;
    }
}
