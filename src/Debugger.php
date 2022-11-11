<?php

namespace Biboletin\Nusoap;

class Debugger
{
    /**
     * Current error string (manipulated by getError/setError)
     *
     * @var string
     */
    private string $errorString = '';
    /**
     * Current debug string (manipulated by debug/appendDebug/clearDebug/getDebug/getDebugAsXMLComment)
     *
     * @var string
     */
    private string $debugString = '';
    /**
     * The debug level for this instance
     *
     * @var int
     */
    private int $debugLevel = 0;

    public function __construct()
    {
        $GLOBALS['_transient']['static']['nusoap_base']['globalDebugLevel'] = 9;
    }

    /**
     * Sets the global debug level, which applies to future instances
     *
     * @param int $level Debug level 0-9, where 0 turns off
     *
     * @return void
     */
    public function setGlobalDebugLevel(int $level): void
    {
        $GLOBALS['_transient']['static']['nusoap_base']['globalDebugLevel'] = $level;
    }

    /**
     * Sets the debug level for this instance
     *
     * @param int $level Debug level 0-9, where 0 turns off
     *
     * @return void
     */
    public function setDebugLevel(int $level): void
    {
        $this->debugLevel = $level;
    }

    /**
     * Sets error string
     *
     * @param string $string error string
     *
     * @return void
     */
    public function setError(string $string): void
    {
        $this->errorString = $string;
    }

    /**
     * Gets the global debug level, which applies to future instances
     *
     * @return int
     */
    public function getGlobalDebugLevel(): int
    {
        return $GLOBALS['_transient']['static']['nusoap_base']['globalDebugLevel'];
    }

    /**
     * Gets the debug level for this instance
     *
     * @return int Debug level 0-9, where 0 turns off
     */
    public function getDebugLevel(): int
    {
        return $this->debugLevel;
    }

    /**
     * Returns error string if present
     *
     * @return mixed error string or false
     */
    public function getError(): mixed
    {
        if (trim($this->errorString) !== '') {
            return $this->errorString;
        }
        return false;
    }

    /**
     * Adds debug data to the instance debug string with formatting
     *
     * @param string $string debug data
     *
     * @return void
     */
    public function debug(string $string): void
    {
        if ($this->debugLevel > 0) {
            $this->appendDebug(
                $this->getmicrotime() . ' ' . get_class($this) . ': ' . $string . "\n"
            );
        }
    }

    /**
     * Adds debug data to the instance debug string without formatting
     *
     * @param string $string debug data
     *
     * @return void
     */
    public function appendDebug(string $string): void
    {
        if ($this->debugLevel > 0) {
            $this->debugString .= $string;
        }
    }

    /**
     * Clears the current debug data for this instance
     *
     * @return void
     */
    public function clearDebug(): void
    {
        $this->debugString = '';
    }

    /**
     * Gets the current debug data for this instance
     *
     * @return string
     */
    public function getDebug(): string
    {
        return $this->debugString;
    }

    /**
     * Gets the current debug data for this instance as an XML comment
     * this may change the contents of the debug data
     *
     * @return string debug data as an XML comment
     */
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
