<?php

declare(strict_types=1);

namespace NL\NlUtils\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

trait QuerySettingsTrait
{
    /**
     * @return $this
     */
    public function disableStorage(): self
    {
        return $this->setDefaultQueryRespectStoragePage(false);
    }

    /**
     * @return $this
     */
    public function enableStorage(): self
    {
        return $this->setDefaultQueryRespectStoragePage(true);
    }

    /**
     * @param bool $respectStoragePage
     * @return $this
     */
    protected function setDefaultQueryRespectStoragePage(bool $respectStoragePage = true): self
    {
        $querySettings = $this
            ->createQuery()
            ->getQuerySettings()
            ->setRespectStoragePage($respectStoragePage);

        $this->setDefaultQuerySettings($querySettings);

        return $this;
    }

    /**
     * @param array $uids
     * @return $this
     */
    public function setStoragePageIds(array $uids): self
    {
        $querySettings = $this
            ->createQuery()
            ->getQuerySettings()
            ->setStoragePageIds($uids);

        $this->setDefaultQuerySettings($querySettings);

        return $this;
    }

    /**
     * @return $this
     */
    public function displayHidden(): self
    {
        return $this->setEnableFieldsToBeIgnored(['disabled']);
    }

    /**
     * @param array $enableFieldsToBeIgnored
     * @return $this
     */
    public function ignoreEnableFields(array $enableFieldsToBeIgnored): self
    {
        return $this->setEnableFieldsToBeIgnored($enableFieldsToBeIgnored);
    }

    /**
     * @param array $enableFieldsToBeIgnored
     * @return $this
     */
    protected function setEnableFieldsToBeIgnored(array $enableFieldsToBeIgnored): self
    {
        /** @var Typo3QuerySettings $querySettings */
        $querySettings = $this
            ->createQuery()
            ->getQuerySettings();

        $querySettings->setIgnoreEnableFields(true);
        $querySettings->setEnableFieldsToBeIgnored($enableFieldsToBeIgnored);

        $this->setDefaultQuerySettings($querySettings);

        return $this;
    }

}
