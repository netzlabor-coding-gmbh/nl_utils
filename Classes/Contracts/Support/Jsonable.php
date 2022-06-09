<?php

declare(strict_types=1);

namespace NL\NlUtils\Contracts\Support;

interface Jsonable
{
    /**
     * @param int $options
     * @return string
     */
    public function toJson(int $options = 0): string;
}
