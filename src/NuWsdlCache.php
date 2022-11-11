<?php

/**
 * The NuSOAP project home is:
 * http://sourceforge.net/projects/nusoap/
 *
 * The primary support for NuSOAP is the mailing list:
 * nusoap-general@lists.sourceforge.net
 */

namespace Biboletin\Nusoap;

use Biboletin\Nusoap\NuWsdl;

/**
 * Caches instances of the wsdl class
 *
 * @author  Scott Nichol <snichol@users.sourceforge.net>
 * @author  Ingo Fischer <ingo@apollon.de>
 * @version $Id: class.wsdlcache.php,v 1.7 2007/04/17 16:34:03 snichol Exp $
 */
class NuWsdlCache
{
    /**
     * $fplock
     *
     * @var mixed
     */
    private mixed $fplock;
    /**
     * $debugString
     *
     * @var string
     */
    private string $debugString;

    /**
     * Constructor
     *
     * @param string $cacheDirectory directory for cache-files
     * @param int $cacheLifetime lifetime for caching-files in seconds or 0 for unlimited
     */
    public function __construct(
        private Debugger $debugger,
        private string $cacheDirectory = '.',
        private int $cacheLifetime = 0
    ) {
        $this->fplock = [];
        $this->debugString = '';
    }

    /**
     * Creates the filename used to cache a wsdl instance
     *
     * @param string $wsdl The URL of the wsdl instance
     *
     * @return string The filename used to cache the instance
     */
    private function createFilename(string $wsdl): string
    {
        return $this->cacheDirectory . '/wsdlcache-' . md5($wsdl);
    }

    /**
     * Gets a wsdl instance from the cache
     *
     * @param string $wsdl The URL of the wsdl instance
     *
     * @return mixed The cached wsdl instance, null if the instance is not in the cache
     */
    public function get(string $wsdl): mixed
    {
        $filename = $this->createFilename($wsdl);
        $s = null;
        if ($this->obtainMutex($filename, 'r')) {
            // check for expired WSDL that must be removed from the cache
            if ($this->cacheLifetime > 0) {
                if (file_exists($filename) && (time() - filemtime($filename) > $this->cacheLifetime)) {
                    unlink($filename);
                    $this->debugger->debug('Expired ' . $wsdl . ' (' . $filename . ') from cache');
                    $this->releaseMutex($filename);
                    return null;
                }
            }
            // see what there is to return
            if (!file_exists($filename)) {
                $this->debugger->debug($wsdl . ' (' . $filename . ') not in cache (1)');
                $this->releaseMutex($filename);
                return null;
            }
            $fp = fopen($filename, 'r');
            if ($fp) {
                $s = implode('', file($filename));
                fclose($fp);
                $this->debugger->debug('Got ' . $wsdl . '(' . $filename . ') from cache');
            }
            if (!$fp) {
                $s = null;
                $this->debugger->debug($wsdl . ' (' . $filename . ') not in cache (2)');
            }
            $this->releaseMutex($filename);
            return ($s !== null) ? unserialize($s) : null;
        }
        $this->debugger->debug('Unable to obtain mutex for ' . $filename . ' in get');
        return null;
    }

    /**
     * Obtains the local mutex
     *
     * @param string $filename The Filename of the Cache to lock
     * @param string $mode The open-mode ("r" or "w") or the file - affects lock-mode
     *
     * @return bool Lock successfully obtained ?!
     */
    private function obtainMutex(string $filename, string $mode): bool
    {
        if (isset($this->fplock[md5($filename)])) {
            $this->debugger->debug('Lock for ' . $filename . ' already exists');
            return false;
        }
        $this->fplock[md5($filename)] = fopen($filename . '.lock', 'w');

        $result = flock($this->fplock[md5($filename)], LOCK_EX);
        if ($mode === 'r') {
            $result = flock($this->fplock[md5($filename)], LOCK_SH);
        }
        return $result;
    }

    /**
     * Adds a wsdl instance to the cache
     *
     * @param NuWsdl $wsdlInstance The wsdl instance to add
     *
     * @return bool WSDL successfully cached
     */
    public function put(NuWsdl $wsdlInstance): bool
    {
        $filename = $this->createFilename($wsdlInstance->wsdl);
        $s = serialize($wsdlInstance);
        if ($this->obtainMutex($filename, 'w')) {
            $fp = fopen($filename, 'w');
            if (!$fp) {
                $this->debugger->debug('Cannot write ' . $wsdlInstance->wsdl . '(' . $filename . ') in cache');
                $this->releaseMutex($filename);
                return false;
            }
            fputs($fp, $s);
            fclose($fp);
            $this->debugger->debug('Put ' . $wsdlInstance->wsdl . '(' . $filename . ') in cache');
            $this->releaseMutex($filename);
            return true;
        }
        $this->debugger->debug('Unable to obtain mutex for ' . $filename . ' in put');
        return false;
    }

    /**
     * Releases the local mutex
     *
     * @param string $filename The Filename of the Cache to lock
     *
     * @return bool Lock successfully released
     */
    private function releaseMutex(string $filename): bool
    {
        $ret = flock($this->fplock[md5($filename)], LOCK_UN);
        fclose($this->fplock[md5($filename)]);
        unset($this->fplock[md5($filename)]);
        if (!$ret) {
            $this->debugger->debug('Not able to release lock for ' . $filename);
        }
        return $ret;
    }

    /**
     * Removes a wsdl instance from the cache
     *
     * @param string $wsdl The URL of the wsdl instance
     *
     * @return bool Whether there was an instance to remove
     */
    public function remove(string $wsdl): bool
    {
        $filename = $this->createFilename($wsdl);
        if (!file_exists($filename)) {
            $this->debugger->debug($wsdl . ' (' . $filename . ') not in cache to be removed');
            return false;
        }
        // ignore errors obtaining mutex
        $this->obtainMutex($filename, 'w');
        $ret = unlink($filename);
        $this->debugger->debug('Removed (' . $ret . ') ' . $wsdl . ' (' . $filename . ') from cache');
        $this->releaseMutex($filename);
        return $ret;
    }

    /**
     * __destruct
     *
     * @return void
     */
    public function __destruct()
    {
        $this->fplock = [];
        $this->debugString = '';
    }
}
