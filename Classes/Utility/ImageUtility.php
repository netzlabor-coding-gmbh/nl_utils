<?php

declare(strict_types=1);

namespace NL\NlUtils\Utility;

use TYPO3\CMS\Core\Imaging\ImageManipulation\CropVariantCollection;
use TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\ImageService;

class ImageUtility
{
    /**
     * @param string $src
     * @param object|null $image
     * @param bool $treatIdAsReference
     * @param null $cropString
     * @param string $cropVariant
     * @param string $fileExtension
     * @param bool $absolute
     * @param array $sizes
     * @return string
     * @throws \Exception
     */
    public static function imageUri(
        string $src = '',
        object $image = null,
        bool $treatIdAsReference = false,
               $cropString = null,
        string $cropVariant = 'default',
        string $fileExtension = '',
        bool $absolute = false,
        array $sizes = []
    ): string
    {
        if (($src === '' && $image === null) || ($src !== '' && $image !== null)) {
            throw new \Exception('You must either specify a string src or a File object.', 1460976233);
        }

        // A URL was given as src, this is kept as is
        if ($src !== '' && preg_match('/^(https?:)?\/\//', $src)) {
            return $src;
        }

        try {
            $imageService = self::getImageService();
            $image = $imageService->getImage($src, $image, $treatIdAsReference);

            if ($cropString === null && $image->hasProperty('crop') && $image->getProperty('crop')) {
                $cropString = $image->getProperty('crop');
            }

            $cropVariantCollection = CropVariantCollection::create((string)$cropString);
            $cropVariant = $cropVariant ?: 'default';
            $cropArea = $cropVariantCollection->getCropArea($cropVariant);
            $processingInstructions = [
                'width' => $sizes['width'] ?? null,
                'height' => $sizes['height'] ?? null,
                'minWidth' => $sizes['minWidth'] ?? null,
                'minHeight' => $sizes['minHeight'] ?? null,
                'maxWidth' => $sizes['maxWidth'] ?? null,
                'maxHeight' => $sizes['maxHeight'] ?? null,
                'crop' => $cropArea->isEmpty() ? null : $cropArea->makeAbsoluteBasedOnFile($image),
            ];
            if (!empty($fileExtension)) {
                $processingInstructions['fileExtension'] = $fileExtension;
            }

            $processedImage = $imageService->applyProcessingInstructions($image, $processingInstructions);
            return $imageService->getImageUri($processedImage, $absolute);
        } catch (ResourceDoesNotExistException $e) {
            // thrown if file does not exist
            throw new \Exception($e->getMessage(), 1509741907, $e);
        } catch (\UnexpectedValueException $e) {
            // thrown if a file has been replaced with a folder
            throw new \Exception($e->getMessage(), 1509741908, $e);
        } catch (\RuntimeException $e) {
            // RuntimeException thrown if a file is outside of a storage
            throw new \Exception($e->getMessage(), 1509741909, $e);
        } catch (\InvalidArgumentException $e) {
            // thrown if file storage does not exist
            throw new \Exception($e->getMessage(), 1509741910, $e);
        }
    }

    /**
     * @return ImageService
     */
    protected static function getImageService(): ImageService
    {
        return GeneralUtility::makeInstance(ImageService::class);
    }
}
