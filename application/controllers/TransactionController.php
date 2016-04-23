<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TransactionController extends CI_Controller
{
    /*
     * This function renew all subscriptions automatically
     * This may insert into CRON
     */
    function updateAllSubscriptions()
    {
        $this->load->model('User');
        $this->load->model('Transaction');
        $users = $this->db->query('SELECT * FROM users WHERE active = 1');
        if ($users->num_rows() > 0) {
            $row = $users->first_row();
            for ($i = 0; $i < $users->num_rows(); $i++) {
                $sms = array(
                    'user_id'   =>  $row->id,
                    'msisdn'    =>  $row->msisdn,
                    'sms'       =>  'Auto renew subscription'
                );
                $result = $this->Transaction->setTransactions($sms);

                if (!isset($result['getBill']->statusCode) || $result['getBill']->statusCode != 'SUCCESS') {
                    echo 'Error updating subscription for ' . $row->msisdn . PHP_EOL;
                } else {
                    echo 'Success subscription update for ' . $row->msisdn . PHP_EOL;
                }

                $row = $users->next_row();
            }
        }
    }

}
