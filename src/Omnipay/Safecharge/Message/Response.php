<?php

namespace Omnipay\Safecharge\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * SafeCharge Response.
 */
class Response extends AbstractResponse
{
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        $this->data = $this->decode($data);
    }

    public function isSuccessful()
    {
        return $this->data['Status'] === 'APPROVED';
    }

    public function isRedirect()
    {
        return isset($this->data['ACSurl']);
    }

    public function getTransactionReference()
    {
        return $this->getTransactionId();
    }

    public function getTransactionId()
    {
        return (
            isset($this->data['TransactionID']) &&
            $this->data['TransactionID'] != ''
        ) ? $this->data['TransactionID'] : null;
    }

    public function getCode()
    {
        return $this->getAuthCode();
    }

    public function getAuthCode()
    {
        return (
            isset($this->data['AuthCode']) &&
            $this->data['AuthCode'] != ''
        ) ? $this->data['AuthCode'] : null;
    }

    public function getToken()
    {
        return (
            isset($this->data['Token']) &&
            $this->data['Token'] != ''
        ) ? $this->data['Token'] : null;
    }

    public function getMessage()
    {
        if (!empty($this->data['ReasonCodes']['Reason'])) {
            return $this->data['ReasonCodes']['Reason'];
        } elseif (!empty($this->data['Reason'])) {
            return $this->data['Reason'];
        }
        return null;
    }

    // public function getRedirectUrl()
    // {
    //     if ($this->isRedirect()) {
    //         return $this->data['ACSurl'];
    //     }
    // }
    //
    // public function getRedirectMethod()
    // {
    //     return 'POST';
    // }
    //
    // public function getRedirectData()
    // {
    //     if ($this->isRedirect()) {
    //         return array(
    //             'PaReq' => $this->data['PaReq'],
    //             'MerchantID' => $this->data['MerchantID'],
    //             'TermURL' => $this->getRequest()->getReturnUrl(),
    //         );
    //     }
    // }

    /**
     * Decode raw ini-style response body
     *
     * @param string The raw response body
     * @return array
     */
    protected function decode($response)
    {
        if ($xml_response = simplexml_load_string($response)) {
            $json_response = json_encode($xml_response);
            $response = json_decode($json_response, true);

            foreach ($response as $key => $val) {
                if (empty($response[$key])) {
                    $response[$key] = '';
                }
            }

            return $response;
        }

        return false;
    }
}
