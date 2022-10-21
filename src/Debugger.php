<?php

namespace Biboletin\Nusoap;

class Debugger
{
    private string $errorString = '';
    private string $debugString = '';
    private int $debugLevel = 0;

    public function __construct()
    {
        //
    }

    public function setGlobalDebugLevel(int $level): void
    {
        $GLOBALS['_transient']['static']['nusoap_base']['globalDebugLevel'] = $level;
    }

    public function setDebugLevel(int $level): void
    {
        $this->debugLevel = $level;
    }

    public function setError(string $string): void
    {
        $this->errorString = $string;
    }

    public function getGlobalDebugLevel(): int
    {
        return $GLOBALS['_transient']['static']['nusoap_base']['globalDebugLevel'];
    }

    public function getDebugLevel(): int
    {
        return $this->debugLevel;
    }

    /**
     * Get error
     *
     * @return bool|string
     */
    public function getError(): mixed
    {
        if (trim($this->errorString) !== '') {
            return $this->errorString;
        }
        return false;
    }

    public function debug(string $string): void
    {
        if ($this->debugLevel > 0) {
            $this->appendDebug(
                $this->getmicrotime() . ' ' . get_class($this) . ': ' . $string . "\n"
            );
        }
    }

    public function appendDebug(string $string): void
    {
        if ($this->debugLevel > 0) {
            $this->debugString .= $string;
        }
    }

    public function clearDebug(): void
    {
        $this->debugString = '';
    }

    public function getDebug(): string
    {
        return $this->debugString;
    }

    public function getDebugAsXmlComment(): string
    {
        while (strpos($this->debugString, '--')) {
            $this->debugString = str_replace('--', '- -', $this->debugString);
        }
        return "<!--\n" . $this->debugString . "\n-->";
    }

    public function getmicrotime(): string
    {
        $sec = time();
        $usec = 0;

        if (function_exists('gettimeofday')) {
            $tod = gettimeofday();
            $sec = $tod['sec'];
            $usec = $tod['usec'];
        }
        $result = date('Y-m-d H:M:S', $sec) . sprintf('%06d', $usec);
        return $result;
    }

    /**
     * Returns a string with the output of var_dump
     *
     * @param mixed $data The variable to var_dump
     *
     * @return string The output of var_dump
     */
    public function varDump($data): string
    {
        ob_start();
        var_dump($data);
        $returnValue = ob_get_contents();
        ob_end_clean();
        return $returnValue;
    }
}
