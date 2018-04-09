<?php
namespace num8er\TranzWarePaymentGateway\Requests;

class TranzWarePaymentGatewayHTTPClient implements TranzWarePaymentGatewayHTTPClientInterface
{
    protected $url;
    protected $body;
    protected $sslCertificate;

    public function __construct
    (
        $url,
        $body = null,
        $sslCertificate = null
    )
    {
        $this->url = $url;
        $this->body = $body;
        $this->sslCertificate = $sslCertificate;
    }

    final public function execute()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_VERBOSE, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/xml',
            'Content-Length: '.strlen($this->body)
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->body);

        if ($this->sslCertificate) {
            $key = $this->sslCertificate['key'];
            $keyPass = $this->sslCertificate['keyPass'];
            $cert = $this->sslCertificate['cert'];
            curl_setopt($ch, CURLOPT_SSLCERT, $cert);
            curl_setopt($ch, CURLOPT_SSLKEY, $key);
            curl_setopt($ch, CURLOPT_SSLKEYPASSWD, $keyPass);
            curl_setopt($ch, CURLOPT_CAINFO, $cert);
            curl_setopt($ch, CURLOPT_CAPATH, $cert);
        }

        $output = curl_exec($ch);
        $info = curl_getinfo($ch);
        return new TranzWarePaymentGatewayHTTPClientResult(
            $output,
            $info
        );
    }
}