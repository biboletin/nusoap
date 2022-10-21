<?php

/**
 * Convert unix timestamp to ISO 8601 compliant date string
 *
 * @param int $timestamp Unix time stamp
 * @param bool $utc Whether the time stamp is UTC or local
 *
 * @return mixed ISO 8601 date string or false
 */
function timestampToIso8601(int $timestamp, bool $utc = true)
{
    $dateString = date('Y-m-d\TH:i:sO', $timestamp);
    $position = strpos($dateString, '+');

    if ($position === false) {
        $position = strpos($dateString, '-');
    }

    if ($position && strlen($dateString) === $position + 5) {
        $dateString = substr($dateString, 0, $position + 3) . ':' . substr($dateString, -2);
    }

    if ($utc === false) {
        return $dateString;
    }
    $pattern = '/([0-9]{4})-([0-9]{2})-([0-9]{2})T([0-9]{2}):([0-9]{2}):([0-9]{2})';
    $pattern .= '(\.[0-9]*)?(Z|[+\-][0-9]{2}:?[0-9]{2})?/';
    if (preg_match($pattern, $dateString, $regs)) {
        $result = sprintf(
            '%04d-%02d-%02dT%02d:%02d:%02dZ',
            $regs[1],
            $regs[2],
            $regs[3],
            $regs[4],
            $regs[5],
            $regs[6],
        );
        return $result;
    }
    return false;
}

/**
 * Convert ISO 8601 compliant date string to unix timestamp
 *
 * @param string $dateString ISO 8601 compliant date string
 *
 * @return mixed Unix timestamp (int) or false
 */
function iso8601ToTimestamp(string $dateString)
{

}