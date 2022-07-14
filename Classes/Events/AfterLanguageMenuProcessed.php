<?php

declare(strict_types=1);

namespace NL\NlUtils\Events;

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

final class AfterLanguageMenuProcessed
{
    /**
     * @var ContentObjectRenderer
     */
    private $cObj;

    /**
     * @var array
     */
    private $contentObjectConfiguration;

    /**
     * @var array
     */
    private $processorConfiguration;

    /**
     * @var array
     */
    private $processedData;

    /**
     * @param ContentObjectRenderer $cObj
     * @param array $contentObjectConfiguration
     * @param array $processorConfiguration
     * @param array $processedData
     */
    public function __construct(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData)
    {
        $this->cObj = $cObj;
        $this->contentObjectConfiguration = $contentObjectConfiguration;
        $this->processorConfiguration = $processorConfiguration;
        $this->processedData = $processedData;
    }

    /**
     * @return ContentObjectRenderer
     */
    public function getCObj(): ContentObjectRenderer
    {
        return $this->cObj;
    }

    /**
     * @return array
     */
    public function getContentObjectConfiguration(): array
    {
        return $this->contentObjectConfiguration;
    }

    /**
     * @return array
     */
    public function getProcessorConfiguration(): array
    {
        return $this->processorConfiguration;
    }

    /**
     * @return array
     */
    public function getProcessedData(): array
    {
        return $this->processedData;
    }

    /**
     * @param array $processedData
     */
    public function setProcessedData(array $processedData): void
    {
        $this->processedData = $processedData;
    }
}
