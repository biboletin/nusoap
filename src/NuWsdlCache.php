<?php

namespace Biboletin\Nusoap;

class NuWsdlCache
{
    private mixed $fplock;
    private string $debugString;

    public function __construct(
        private Debugger $debugger,
        private string $cacheDirectory = '.',
        private int $cacheLifetime = 0
    ) {
        $this->fplock = [];
        $this->debugString = '';
    }

    private function createFilename(string $wsdl): string
    {
        return $this->cacheDirectory . '/wsdlcache-' . md5($wsdl);
    }

    public function get(string $wsdl): mixed
    {
        $filename = $this->createFilename($wsdl);
        if ($this->obtainMutex($filename, 'r')) {
            // check for expired WSDL that must be removed from the cache
            if ($this->cache_lifetime > 0) {
                if (file_exists($filename) && (time() - filemtime($filename) > $this->cache_lifetime)) {
                    unlink($filename);
                    $this->debugger->debug("Expired $wsdl ($filename) from cache");
                    $this->releaseMutex($filename);
                    return null;
                }
            }
            // see what there is to return
            if (!file_exists($filename)) {
                $this->debugger->debug("$wsdl ($filename) not in cache (1)");
                $this->releaseMutex($filename);
                return null;
            }
            $fp = @fopen($filename, 'r');
            if ($fp) {
                $s = implode('', @file($filename));
                fclose($fp);
                $this->debugger->debug("Got $wsdl ($filename) from cache");
            } else {
                $s = null;
                $this->debugger->debug("$wsdl ($filename) not in cache (2)");
            }
            $this->releaseMutex($filename);
            return (!is_null($s)) ? unserialize($s) : null;
        } else {
            $this->debugger->debug("Unable to obtain mutex for $filename in get");
        }
        return null;
    }

    public function __destruct()
    {
        $this->fplock = [];
        $this->debugString = '';
    }
}
