<?php

namespace IproSync\Ipro;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;

class DateTime
{
    public static function createFromMultipleFormats(array $formats, string $dateString): Carbon
    {
        $dateObj   = null;
        $lastError = null;
        foreach ($formats as $format) {
            try {
                if ($dateObj = Carbon::createFromFormat($format, $dateString)) {
                    break;
                }
            } catch (InvalidFormatException $e) {
                $lastError = $e;
            }
        }
        if (!$dateObj && $lastError) {
            throw $lastError;
        }

        return $dateObj;
    }
}
