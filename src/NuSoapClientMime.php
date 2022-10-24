<?php

/**
 * $Id: nusoapmime.php,v 1.13 2010/04/26 20:15:08 snichol Exp $
 *
 * NuSOAP - Web Services Toolkit for PHP
 *
 * Copyright (c) 2002 NuSphere Corporation
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * The NuSOAP project home is:
 * http://sourceforge.net/projects/nusoap/
 *
 * The primary support for NuSOAP is the mailing list:
 * nusoap-general@lists.sourceforge.net
 *
 * If you have any questions or comments, please email:
 *
 * Dietrich Ayala
 * dietrich@ganx4.com
 * http://dietrich.ganx4.com/nusoap
 *
 * NuSphere Corporation
 * http://www.nusphere.com
 */

namespace Biboletin\Nusoap;

use Biboletin\Nusoap\Debugger;
use Biboletin\Nusoap\NuSoapClient;

/**
 * Renamed from nusoap_client_mime to NuSoapClientMime
 *
 * NuSoapClientMime client supporting MIME attachments defined at
 * http://www.w3.org/TR/SOAP-attachments.  It depends on the PEAR Mail_MIME library.
 *
 * @author  Scott Nichol <snichol@users.sourceforge.net>
 * @author  to Guillaume and Henning Reich for posting great attachment code to the mail list
 * @version $Id: nusoapmime.php,v 1.13 2010/04/26 20:15:08 snichol Exp $
 */
class NuSoapClientMime extends NuSoapClient
{
    /**
     * Each array element in the return is an associative array with keys
     * data, filename, contenttype, cid
     *
     * @var array
     */
    private array $requestAttachments = [];
    /**
     * Each array element in the return is an associative array with keys
     * data, filename, contenttype, cid
     *
     * @var array
     */
    private array $responseAttachments;
    /**
     * Mime content type
     *
     * @var string
     */
    private string $mimeContentType;

    public function __construct(private Debugger $debugger)
    {
        $this->debugger = $debugger;
        $this->requestAttachments = [];
        $this->responseAttachments = [];
        $this->mimeContentType = '';
    }

    /**
     * Adds a MIME attachment to the current request.
     *
     * If the $data parameter contains an empty string, this method will read
     * the contents of the file named by the $filename parameter.
     *
     * If the $cid parameter is false, this method will generate the cid.
     *
     * @param string $data The data of the attachment
     * @param string $filename The filename of the attachment (default is empty string)
     * @param string $contenttype The MIME Content-Type of the attachment (default is application/octet-stream)
     * @param mixed $cid The content-id (cid) of the attachment (default is false)
     *
     * @return string The content-id (cid) of the attachment
     */
    public function addAttachment(
        string $data,
        string $filename = '',
        string $contenttype = 'application/octet-stream',
        mixed $cid = false
    ): string {
        if (!$cid) {
            $cid = md5(uniqid((string) time()));
        }
        $info = [];
        $info['data'] = $data;
        $info['filename'] = $filename;
        $info['contenttype'] = $contenttype;
        $info['cid'] = $cid;

        $this->requestAttachments[] = $info;

        return $cid;
    }

    /**
     * Clears the MIME attachments for the current request.
     *
     * @return void
     */
    public function clearAttachments(): void
    {
        $this->requestAttachments = [];
    }

    /**
     * Gets the MIME attachments from the current response.
     *
     * Each array element in the return is an associative array with keys
     * data, filename, contenttype, cid.  These keys correspond to the parameters
     * for addAttachment.
     *
     * @return array
     */
    public function getAttachments(): array
    {
        return $this->responseAttachments;
    }

    /**
     * Gets the HTTP body for the current request.
     *
     * @param string $soapmsg The SOAP payload
     *
     * @return string The HTTP body, which includes the SOAP payload
     */
    private function getHttpBody(string $soapmsg): string
    {
        if (count($this->requestAttachments) > 0) {
            return parent::getHttpBody($soapmsg);
        }
        $params = [];
        $params['content_type'] = 'multipart/related; type="text/xml"';
        $mimeMessage = new \Mail_mimePart('', $params);
        unset($params);
        $params = [];
        $params['content_type'] = 'text/xml';
        $params['encoding'] = '8bit';
        $params['charset'] = parent::getSoapDefEncoding();
        $mimeMessage->addSubpart($soapmsg, $params);
        foreach ($this->requestAttachments as $att) {
            unset($params);
            $params = [];
            $params['content_type'] = $att['contenttype'];
            $params['encoding'] = 'base64';
            $params['disposition'] = 'attachment';
            $params['dfilename'] = $att['filename'];
            $params['cid'] = $att['cid'];

            if ($att['data'] === '' && $att['filename'] !== '') {
                $data = '';
                if ($fd = fopen($att['filename'], 'rb')) {
                    $data = fread($fd, filesize($att['filename']));
                    fclose($fd);
                }
                $mimeMessage->addSubpart($data, $params);
            }
            if ($att['data'] !== '' && $att['filename'] === '') {
                $mimeMessage->addSubpart($att['data'], $params);
            }
        }

        $output = $mimeMessage->encode();
        $mimeHeaders = $output['headers'];

        foreach ($mimeHeaders as $k => $v) {
            $this->debugger->debug('MIME header ' .  $k . ':' . $v);
            if (strtolower($k) === 'content-type') {
                // PHP header() seems to strip leading whitespace starting
                // the second line, so force everything to one line
                $this->mimeContentType = str_replace("\r\n", ' ', $v);
            }
        }

        return $output['body'];
    }

    /**
     * Gets the HTTP content type for the current request.
     *
     * Note: getHTTPBody must be called before this.
     *
     * @return string The HTTP content type for the current request.
     */
    private function getHttpContentType(): string
    {
        if (count($this->requestAttachments) > 0) {
            return $this->mimeContentType;
        }
        return parent::getHttpContentType();
    }

    /**
     * Gets the HTTP content type charset for the current request.
     * returns false for non-text content types.
     *
     * Note: getHTTPBody must be called before this.
     *
     * @return mixed The HTTP content type charset for the current request.
     */
    private function getHttpContentTypeCharset(): mixed
    {
        if (count($this->requestAttachments) > 0) {
            return false;
        }
        return parent::getHttpContentTypeCharset();
    }

    /**
     * Processes SOAP message returned from server
     *
     * @param array $headers The HTTP headers
     * @param string $data Unprocessed response data from server
     *
     * @return mixed Value of the message, decoded into a PHP type
     */
    private function parseResponse(array $headers, string $data): mixed
    {
        $this->debuggger->debug('Entering parseResponse() for payload of length ' .
            strlen($data) . ' and type of ' . $headers['content-type']);
        $this->responseAttachments = [];
        if (strstr($headers['content-type'], 'multipart/related')) {
            $this->debuggger->debug('Decode multipart/related');
            $input = '';
            foreach ($headers as $k => $v) {
                $input .= $k . ':' . $v . "\r\n";
            }
            $params = [];
            $params['input'] = $input . "\r\n" . $data;
            $params['include_bodies'] = true;
            $params['decode_bodies'] = true;
            $params['decode_headers'] = true;

            $structure = \Mail_mimeDecode::decode($params);

            foreach ($structure->parts as $part) {
                if (!isset($part->disposition) && (strstr($part->headers['content-type'], 'text/xml'))) {
                    $this->debuggger->debug('Have root part of type ' . $part->headers['content-type']);
                    $root = $part->body;
                    $return = parent::parseResponse($part->headers, $part->body);
                } else {
                    $this->debuggger->debug('Have an attachment of type ' . $part->headers['content-type']);
                    $info['data'] = $part->body;
                    $info['filename'] = isset($part->d_parameters['filename']) ? $part->d_parameters['filename'] : '';
                    $info['contenttype'] = $part->headers['content-type'];
                    $info['cid'] = $part->headers['content-id'];
                    $this->responseAttachments[] = $info;
                }
            }

            if (isset($return)) {
                $this->responseData = $root;
                return $return;
            }

            $this->debugger->setError('No root part found in multipart/related content');
            return '';
        }
        $this->debuggger->debug('Not multipart/related');
        return parent::parseResponse($headers, $data);
    }

    public function __destruct()
    {
        $this->requestAttachments = [];
        $this->responseAttachments = [];
        $this->mimeContentType = '';
    }
}
