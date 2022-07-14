<?php

declare(strict_types=1);

namespace NL\NlUtils\ViewHelpers;

use FluidTYPO3\Vhs\Traits\TemplateVariableViewHelperTrait;
use NL\NlUtils\Events\AfterLanguageMenuProcessed;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Frontend\DataProcessing\LanguageMenuProcessor;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

class LanguageMenuViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;
    use TemplateVariableViewHelperTrait;

    /**
     * @var boolean
     */
    protected $escapeChildren = false;

    /**
     * @var boolean
     */
    protected $escapeOutput = false;

    /**
     * @return void
     */
    public function initializeArguments(): void
    {
        $this->registerAsArgument();
        $this->registerArgument('processorConfiguration', 'array', 'LanguageMenuProcessor configuration', false, []);
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return mixed|string
     * @throws \TYPO3\CMS\Extbase\Object\Exception
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        /** @var ConfigurationManagerInterface $configurationManager */
        $configurationManager = GeneralUtility::makeInstance(ConfigurationManagerInterface::class);

        /** @var LanguageMenuProcessor $languageMenuProcessor */
        $languageMenuProcessor = GeneralUtility::makeInstance(LanguageMenuProcessor::class);

        $languageMenu = $languageMenuProcessor->process($configurationManager->getContentObject(), [], $arguments['processorConfiguration'], []);

        $event = new AfterLanguageMenuProcessed($configurationManager->getContentObject(), [], $arguments['processorConfiguration'], $languageMenu);

        $eventDispatcher = GeneralUtility::makeInstance(EventDispatcherInterface::class);
        $eventDispatcher->dispatch($event);

        return static::renderChildrenWithVariableOrReturnInputStatic(
            $event->getProcessedData()['languagemenu'] ?? [],
            $arguments['as'],
            $renderingContext,
            $renderChildrenClosure
        );
    }
}
