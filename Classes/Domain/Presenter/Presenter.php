<?php

declare(strict_types=1);

namespace NL\NlUtils\Domain\Presenter;

use NL\NlUtils\Contracts\Support\Arrayable;
use NL\NlUtils\Contracts\Support\Jsonable;
use NL\NlUtils\Utility\DomainObjectUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

abstract class Presenter implements Arrayable, Jsonable
{
    /**
     * @var AbstractEntity
     */
    protected $entity;

    /**
     * @param AbstractEntity $entity
     */
    public function __construct(AbstractEntity $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @param AbstractEntity $entity
     * @return Presenter
     */
    public static function make(AbstractEntity $entity): Presenter
    {
        return GeneralUtility::makeInstance(static::class, $entity);
    }

    /**
     * @param array $entities
     * @param string|null $as
     * @return array
     */
    public static function collect(array $entities, string $as = null): array
    {
        $presenters = [];

        foreach ($entities as $entity) {
            $presenters[] = static::make($entity);
        }

        return (null !== $as) ? DomainObjectUtility::collectAs($presenters, $as) : $presenters;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->entity->toArray();
    }

    /**
     * @param int $options
     * @return string
     */
    public function toJson(int $options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
