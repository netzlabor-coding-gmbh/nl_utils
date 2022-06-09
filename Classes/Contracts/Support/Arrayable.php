<?php

declare(strict_types=1);

namespace NL\NlUtils\Contracts\Support;

interface Arrayable
{
    /**
     * @return array
     */
    public function toArray(): array;
}
