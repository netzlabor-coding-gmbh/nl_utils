<?php

declare(strict_types=1);

namespace NL\NlUtils\Domain\Presenter;

use TYPO3\CMS\Core\Utility\ClassNamingUtility;

trait PresentableTrait
{
    /**
     * @var Presenter
     */
    protected $presenter;

    /**
     * @var string
     */
    protected $presenterClass;

    /**
     * @return Presenter
     */
    public function getPresenter(): Presenter
    {
        if (!$this->presenter) {
            if (!$this->presenterClass) {
                $this->presenterClass = \NL\NlUtils\Utility\ClassNamingUtility::translateModelNameToPresenterName(get_class($this));
            }

            $this->presenter = call_user_func([$this->presenterClass, 'make'], $this);
        }

        return $this->presenter;
    }
}
