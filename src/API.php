<?php

namespace DataLinx\SqualoMail;

use DataLinx\SqualoMail\Exceptions\APIException;

class API
{
    /**
     * @var string API key
     */
    public string $api_key;

    /**
     * @param string $api_key
     */
    public function __construct(string $api_key)
    {
        $this->api_key = $api_key;
    }

    /**
     * Make sure the state of the API is valid
     *
     * @throws APIException
     */
    public function validate()
    {
        foreach (get_object_vars($this) as $key => $val) {
            if (empty($val)) {
                throw new APIException(sprintf('API attribute "%s" is required', $key));
            }
        }
    }

    /**
     * Get the API URL
     *
     * @return string
     */
    public function getUrl(): string
    {
        return 'https://api.squalomail.com/v1/';
    }

    /**
     * Send a request to the API
     *
     * @param string $endpoint Endpoint/URI
     * @param array $data Request data
     * @param bool $post Make a POST requst with a JSON body
     * @return array Response array
     * @throws APIException
     */
    public function sendRequest(string $endpoint, array $data, bool $post = false): array
    {
        $ch = curl_init();

        if ($post) {
            curl_setopt_array($ch, [
                CURLOPT_URL => $this->getUrl() . $endpoint,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                ],
            ]);
        } else {
            curl_setopt_array($ch, [
                CURLOPT_URL => $this->getUrl() . $endpoint .'?'. http_build_query($data),
            ]);
        }

        // Common cURL options
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FAILONERROR => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_ENCODING => 'UTF-8',
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
        ]);

        if (getenv('SQUALOMAIL_DEBUG')) {
            $fp = fopen('build/curl.log', 'w');
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
            curl_setopt($ch, CURLOPT_STDERR, $fp);
        }

        $response = curl_exec($ch);

        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        $err_no = curl_errno($ch);

        curl_close($ch);

        if (empty($response)) {
            throw new APIException('SqualoMail API request failed! cURL error: '. $error .' (err.no.: '. $err_no .', HTTP code: '. $code .')', $code);
        }

        return json_decode($response, true);
    }
}
