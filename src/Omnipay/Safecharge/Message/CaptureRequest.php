<?php

namespace Omnipay\Safecharge\Message;

/**
 * SafeCharge Settle Request
 */
class CaptureRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('amount', 'currency');

        $data = parent::getData();
        $data['sg_TransType'] = 'Settle';
        $data['sg_CCToken'] = $this->getToken();
        $data['sg_ExpMonth'] = $this->getExpMonth();
        $data['sg_ExpYear'] = $this->getExpYear();
        $data['sg_AuthCode'] = $this->getAuthCode();
        $data['sg_TransactionID'] = (int) $this->getTransactionId();
        $data['sg_Amount'] = $this->getAmount();
        $data['sg_Currency'] = $this->getCurrency();

        return $data;
    }
}
