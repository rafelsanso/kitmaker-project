<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserController extends CI_Controller {

	//function __construct() {
	//    parent::Controller();
	//}

    function listUsers() {
		$this->load->model('User');
		$contentData['users'] = $this->User->getUsers(10);

		//check if send SMS
		$sms = $this->input->post();
		if ($sms) {
			$this->load->helper('url');
			$this->load->model('User');
			$this->load->model('Transaction');

			$userData = $this->User->findUser($sms['user_id']);
			$contentData['transaction'] = $this->Transaction->setTransactions($sms);
		}

		$this->load->view('includes/header');
		$this->load->view('pages_users', $contentData);
		$this->load->view('includes/footer');
	}

	function register() {
		$this->load->model('User');
		$this->load->model('Transaction');
		$newUser = $this->input->post();
		$contentData['message'] = '';

		// Insert temporally
		if ($newUser) {
			$contentData['message'] = $this->User->insertUser($newUser);
			$contentData['user'] = $newUser;
		}
		// Try to add credits with transaction
		if (isset($contentData['message']['user_id'])) {
			$sms = array(
				'user_id'	=>	$contentData['message']['user_id'],
				'msisdn'	=>	$newUser['msisdn'],
				'sms'		=>	$newUser['sms']
			);

			$contentData['transaction'] = $this->Transaction->setTransactions($sms);

			if (!isset($contentData['transaction']['getBill']->statusCode) || $contentData['transaction']['getBill']->statusCode != 'SUCCESS') {
				// No founds. Remove user
				$this->User->deleteUser($newUser['msisdn']);
				$contentData['message'] = array('error' => 'Error processing SMS registration. Please try again.');
			}
		}

		$this->load->view('includes/header');
		$this->load->view('pages_registration', $contentData);
		$this->load->view('includes/footer');
	}

	function update($id) {
		$this->load->model('User');

		$userData = $this->User->findUser($id);
		$newData = $this->input->post();
		$contentData['message'] = '';
		$contentData['user'] = $userData;

		if ($userData && $newData) {
			$contentData['message'] = $this->User->updateUser($newData);
			// refresh data information
			$contentData['user'] = $this->User->findUser($id);
		}

		$this->load->view('includes/header');
		$this->load->view('pages_update', $contentData);
		$this->load->view('includes/footer');
	}

	public function updateSubscription($id) {
		$this->load->helper('url');
		$this->load->model('User');

		$userData = $this->User->findUser($id);
		// Toggle subscription
		$newStatus = ($userData[0]->active == 1) ? 0 : 1;

		$this->User->updateSubscriptionStatus($userData[0]->msisdn, $newStatus);

		redirect('/UserController/listUsers/', 'refresh');
	}

}