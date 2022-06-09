<?php

declare(strict_types=1);

namespace NL\NlUtils\Contracts\Presenter;

use NL\NlUtils\Domain\Presenter\Presenter;

interface PresentableInterface
{
    public function getPresenter(): Presenter;
}
