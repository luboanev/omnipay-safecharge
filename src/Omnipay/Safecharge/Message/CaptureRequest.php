<?php

namespace Omnipay\Safecharge\Message;

/**
 * SafeCharge Settle Request
 */
class CaptureRequest extends AbstractRequest
{
    public function getData()
    {
        $data = parent::getData();

        $this->validate('amount', 'currency');

        $data['sg_TransType'] = 'Settle';

        $this->validate('authCode', 'transactionReference');

        $data['sg_AuthCode'] = $this->getAuthCode();
        $data['sg_TransactionID'] = (int) $this->getTransactionReference();
        $data['sg_Amount'] = $this->getAmount();
        $data['sg_Currency'] = $this->getCurrency();

//        var_dump($data);
//        exit;

        return $data;
    }
}
