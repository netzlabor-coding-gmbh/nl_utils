<?php

declare(strict_types=1);

namespace NL\NlUtils\Evaluation;

use NL\NlUtils\Utility\EvalcoordinatesUtility;

class LatitudeEvaluation
{
    /**
     * @return string
     */
    public function returnFieldJS(): string
    {
        return '
      return value;
    ';
    }

    /**
     * @param string $value
     * @return string|null
     */
    public function evaluateFieldValue(string $value): ?string
    {
        // test if we have any latitude
        if ($value && $value !== '') {
            return EvalcoordinatesUtility::formatLatitude($value);
        }
        return null;
    }

    /**
     * @param array $parameters
     * @return string|null
     */
    public function deevaluateFieldValue(array $parameters): ?string
    {
        // test if we have any latitude
        if ($parameters['value'] && $parameters['value'] !== '') {
            $parameters['value'] = EvalcoordinatesUtility::formatLatitude($parameters['value']);
        }
        return $parameters['value'];
    }
}
