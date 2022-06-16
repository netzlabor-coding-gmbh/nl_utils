<?php

declare(strict_types=1);

namespace NL\NlUtils\Evaluation;

use NL\NlUtils\Utility\EvalcoordinatesUtility;

class LongitudeEvaluation
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
        // test if we have any longitude
        if ($value && $value !== '') {
            return EvalcoordinatesUtility::formatLongitude($value);
        }
        return null;
    }

    /**
     * @param array $parameters
     * @return mixed|string
     */
    public function deevaluateFieldValue(array $parameters): ?string
    {
        // test if we have any longitude
        if ($parameters['value'] && $parameters['value'] != '') {
            $parameters['value'] = EvalcoordinatesUtility::formatLongitude($parameters['value']);
        }
        return $parameters['value'];
    }
}
