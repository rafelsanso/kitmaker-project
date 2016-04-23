<?php if (! defined('BASEPATH')) exit('No direct script access');

class Transaction extends CI_Model
{

    function __construct()
    {

        parent::__construct();

        $id 		            = null;
        $fk_user 	          = null;
        $request_xml 		    = null;
        $response_xml 	    = null;
        $response_code      = null;
        $response_message 	= null;
        $response_txid      = null;
        $date               = null;

    }

    /*
     * Lists a number of users
     */
    private function sendSMS($sms)
    {
        $curl = curl_init();
        $xml_request = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n
            <request>\n
                <shortcode>New SMS</shortcode>\n
                <text>" . $sms['sms'] . "</text>\n
                <msisdn>" . $sms['msisdn'] . "</msisdn>\n
                <transaction>" . rand(11111111,99999999) . "</transaction>\n
            </request>";

        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://52.30.94.95/send_sms",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $xml_request,
          CURLOPT_HTTPHEADER => array(
            "authorization: Basic cnNhbnNvOnJ5Z0Y4Wjdr",
            "cache-control: no-cache",
            "Content-Type: text/xml"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $responseXML = simplexml_load_string($response);

        $this->saveTransaction(
          $sms['msisdn'],
          'sendSMS',
          $xml_request,
          $response,
          $responseXML->statusCode,
          $responseXML->statusMessage,
          $responseXML->txId
        );

        if ($err) {
          return "cURL Error #:" . $err;
        } else {
          return $responseXML;
        }
    }

    private function getToken($sms)
    {
        $curl = curl_init();

        $xml_request = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n
            <request>\n
                <transaction>" . rand(11111111,99999999) . "</transaction>\n
            </request>";

        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://52.30.94.95/token",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $xml_request,
          CURLOPT_HTTPHEADER => array(
            "authorization: Basic cnNhbnNvOnJ5Z0Y4Wjdr",
            "cache-control: no-cache",
            "Content-Type: text/xml"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $responseXML = simplexml_load_string($response);

        $this->saveTransaction(
          $sms['msisdn'],
          'getToken',
          $xml_request,
          $response,
          $responseXML->statusCode,
          $responseXML->statusMessage,
          $responseXML->txId
        );

        if ($err) {
          return "cURL Error #:" . $err;
        } else {
          return $responseXML;
        }
    }

    private function getBill($token, $sms)
    {
        $curl = curl_init();

        $xml_request = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r
            <request>\r
                <transaction>" . rand(11111111,99999999) . "</transaction>\r
                <msisdn>" . $sms['msisdn'] . "</msisdn>\r
                <amount>5</amount>\r
                <token>" . $token . "</token>\r
            </request>";

        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://52.30.94.95/bill",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $xml_request,
          CURLOPT_HTTPHEADER => array(
            "authorization: Basic cnNhbnNvOnJ5Z0Y4Wjdr",
            "cache-control: no-cache",
            "Content-Type: text/xml"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $responseXML = simplexml_load_string($response);

        $this->saveTransaction(
          $sms['msisdn'],
          'getBill',
          $xml_request,
          $response,
          $responseXML->statusCode,
          $responseXML->statusMessage,
          $responseXML->txId
        );

        if ($err) {
          return "cURL Error #:" . $err;
        } else {
          return $responseXML;
        }
    }

    function setTransactions($sms)
    {
      $transactionResponse = array(
        'sendSMS' => '',
        'getToken'=> '',
        'getBill' => ''
      );
      $sendResponse = $this->sendSMS($sms);
      $transactionResponse['sendSMS'] = $sendResponse;

      if ($sendResponse->statusCode == 'SUCCESS') {
        $tokenResponse = $this->getToken($sms);
        $transactionResponse['getToken'] = $tokenResponse;

        if ($tokenResponse->statusCode == 'TOKEN_SUCCESS') {
          $billResponse = $this->getBill($tokenResponse->token, $sms);
          $transactionResponse['getBill'] = $billResponse;

          if ($billResponse->statusCode == 'SUCCESS') {
            // Add founds to the user
            $this->load->model('User');
            $userData = $this->User->findUser($sms['user_id']);
            $this->createTransaction($userData[0]);
          }
        }

      }

      return $transactionResponse;

    }

    function saveTransaction($msisdn, $type, $requestXML, $responseXML, $responseCode, $responseMessage, $responseTxid)
    {
      $data = array(
        'msisdn'              => $msisdn,
        'type'                => $type,
        'request_xml'         => $requestXML,
        'response_xml'        => $responseXML,
        'response_code'       => $responseCode,
        'response_message'    => $responseMessage,
        'response_txid'       => $responseTxid,
        'date'                => time()
      );

      $this->db->insert('transactions', $data);
    }

    function createTransaction($userData)
    {
      // Number of new credits
      $credits = 5;

      $this->load->model('User');

      // Add new credits
      $this->User->updateUserCredits($userData->id, ($credits + $userData->credits));

      $this->load->model('Register');
      $this->Register->createRegister(array(
                'entity'   => 'users',
                'fk_id'    => $userData->msisdn,
                'log_type' => 'Add credits',
                'value'    => $credits,
                'date'     => time()
              )
      );
    }
}