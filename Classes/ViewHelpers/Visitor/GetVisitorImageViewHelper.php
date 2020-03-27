<?php
declare(strict_types=1);
namespace In2code\Lux\ViewHelpers\Visitor;

use In2code\Lux\Domain\Model\Visitor;
use In2code\Lux\Utility\FileUtility;
use In2code\Lux\Utility\ObjectUtility;
use In2code\Lux\Utility\StringUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\Exception;
use TYPO3\CMS\Extbase\Service\ImageService;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class GetVisitorImageViewHelper
 */
class GetVisitorImageViewHelper extends AbstractViewHelper
{
    /**
     * @var string
     */
    protected $defaultFile = 'typo3conf/ext/lux/Resources/Public/Images/AvatarDefault.svg?a=b';

    /**
     * Size in px
     *
     * @var int
     */
    protected $size = 150;

    /**
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument('visitor', Visitor::class, 'visitor', true);
    }

    /**
     * @return string
     * @throws Exception
     */
    public function render(): string
    {
        $url = '';
        $url = $this->getImageUrlFromFrontenduser($url);
        $url = $this->getImageUrlFromGravatar($url);
        $url = $this->getImageFromGoogle($url);
        $url = $this->getDefaultUrl($url);
        return $url;
    }

    /**
     * @param string $url
     * @return string
     * @throws Exception
     */
    protected function getImageUrlFromFrontenduser(string $url): string
    {
        if ($this->isVisitorWithFrontendUserImage()) {
            foreach ($this->getVisitor()->getFrontenduser()->getImage() as $imageObject) {
                $file = $imageObject->getOriginalResource()->getOriginalFile();
                $imageService = ObjectUtility::getObjectManager()->get(ImageService::class);
                $image = $imageService->getImage('', $file, false);
                $processConfiguration = [
                    'width' => (string)$this->size . 'c',
                    'height' => (string)$this->size . 'c'
                ];
                $processedImage = $imageService->applyProcessingInstructions($image, $processConfiguration);
                $url = $imageService->getImageUri($processedImage, true);
            }
        }
        return $url;
    }

    /**
     * @param string $url
     * @return string
     */
    protected function getImageUrlFromGravatar(string $url): string
    {
        if (empty($url) && $this->getVisitor()->isIdentified()) {
            $gravatarUrl = 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($this->getVisitor()->getEmail())))
                . '?d=' . urlencode($this->getDefaultUrl($url)) . '&s=' . $this->size;
            $header = GeneralUtility::getUrl($gravatarUrl, 2);
            if (!empty($header)) {
                $url = $gravatarUrl;
            }
        }
        return $url;
    }

    /**
     * @param string $url
     * @return string
     */
    protected function getImageFromGoogle(string $url): string
    {
        if (empty($url) && $this->getVisitor()->isIdentified()
            && class_exists(\Buchin\GoogleImageGrabber\GoogleImageGrabber::class)) {
            $images = \Buchin\GoogleImageGrabber\GoogleImageGrabber::grab($this->getVisitor()->getEmail());
            foreach ((array)$images as $image) {
                if (!empty($image['url']) && FileUtility::isImageFile($image['url'])) {
                    $url = $image['url'];
                    break;
                }
            }
        }
        return $url;
    }

    /**
     * @param string $url
     * @return string
     */
    protected function getDefaultUrl(string $url): string
    {
        if (empty($url)) {
            $url = StringUtility::getCurrentUri() . $this->defaultFile;
        }
        return $url;
    }

    /**
     * @return Visitor
     */
    protected function getVisitor(): Visitor
    {
        /** @var Visitor $visitor */
        $visitor = $this->arguments['visitor'];
        return $visitor;
    }

    /**
     * @return bool
     */
    protected function isVisitorWithFrontendUserImage(): bool
    {
        return $this->getVisitor()->getFrontenduser() !== null
            && $this->getVisitor()->getFrontenduser()->getImage() !== null
            && $this->getVisitor()->getFrontenduser()->getImage()->count() > 0;
    }
}