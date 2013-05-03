<?php

// Classes
    class TruliooProfilePlus
    {
            private $apiKey;
            private $token;
            private $provider;

            private $cl;

            public function __construct($apiKey, $token, $provider = 'fb')
            {
                $this->apiKey   = $apiKey;
                $this->token    = $token;
                $this->provider = $provider;

                $this->cl = null;
            }

            public function fetchCL($timeoutInSeconds = null)
            {
                // If already have the confidence level (CL), just return the cached version. Do NOT go fetch it again.
                    if (  !is_null($this->cl)  ) {
            /////////// RETURN
                        return $this->cl;
                    }


                // Go fetch the confience level class using Trulioo's HTTP-based API.
                    $httpResponseCode    = null;
                    $httpResponseContent = null;

                    $beginTime = time();
                    while (  202 === ($httpResponseCode = $this->makeHttpRequest($httpResponseContent))  ) {

                        if (  !is_null($timeoutInSeconds)  ) {

                            $diff = time() - $beginTime;
                            if (  $diff > $timeoutInSeconds  ) {
                    /////////// BREAK
                                break;
                            }
                        }

                        sleep(1); // Wait (at least) one second before hitting the API again!!!

                    } // while


                // Handle the HTTP response.
                    switch ($httpResponseCode) {

                        case 200:

                                if (  is_string($httpResponseContent) && '' != $httpResponseContent  ) {

                                    $jsonArr = json_decode($httpResponseContent, true);



                                    if (  is_array($jsonArr)  ) {
                                        if (  isset($jsonArr['ok'])   &&   1 == $jsonArr['ok']
                                           && isset($jsonArr['code']) && 200 == $jsonArr['code']
                                           && isset($jsonArr['body']) && is_array($jsonArr['body'])
                                           && isset($jsonArr['body']['confidence']) && is_numeric($jsonArr['body']['confidence'])
                                           ) {

                                            $this->cl = $jsonArr['body']['confidence'];
                                        }
                                    }

                                }

                        break;

                        default:
if (isset($_GET['debug'])) {

        if ($_GET['debug'] == TRUE) {
                echo '<pre>';
                echo '$httpResponseCode    = ', var_export($httpResponseCode,    true), "\n\n";
                    echo '$httpResponseContent = ', var_export($httpResponseContent, true), "\n\n";
                echo '</pre>';
        };
      };

                        break;

                    } // switch

                    //DEBUG


                // Return.
                    return $this->cl;
            }


            private function makeHttpRequest(&$httpResponseContent)
            {
                // Construct API URL.
                    $queryParameters = array();
                    $queryParameters['apiKey']   = $this->apiKey;
                    $queryParameters['provider'] = $this->provider;
                    $queryParameters['token']    = $this->token;

                    $apiHref = 'https://api.trulioo.com/v1/profilePlus?' . http_build_query($queryParameters);

                // Initialize cURL.
                    $ch = curl_init();


                // Configure cURL.
                    curl_setopt($ch, CURLOPT_TIMEOUT        , 30);
                    curl_setopt($ch, CURLOPT_HEADER         , false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION , true);

                    curl_setopt($ch, CURLOPT_URL  , $apiHref);
                    curl_setopt($ch, CURLOPT_POST , 0);


                // Make HTTP request using cURL.
                    $response = curl_exec($ch);

                    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                    //DEBUG
                    //echo '$code =     ', var_export($code, true), "\n\n";
                    //echo '$response = ', var_export($response, true), "\n\n";


                // Clean up things with cURL.
                    curl_close ($ch);
                    unset($ch);


                // Return.
                // (We are returning 2 different ways here!)
                    $httpResponseContent = $response;
                    return $code;
            }


    } // class TruliooProfilePlus

?>