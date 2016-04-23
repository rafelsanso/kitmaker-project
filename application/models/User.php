<?php if (! defined('BASEPATH')) exit('No direct script access');

class User extends CI_Model {

    function __construct() {

        parent::__construct();

        $id 		= null;
        $username 	= null;
        $email 		= null;
        $mobile 	= null;
        $credits 	= null;
        $active 	= null;
        $date 		= null;

    }

    /*
     * Lists a number of users
     */
    function getUsers($number = 10)
    {
        $query = $this->db->get('users', $number);
        return $query->result();
    }

    /*
     * Lists a number of users
     */
    function findUser($id)
    {
        $query = $this->db->get_where('users', array('id' => $id));
        return $query->result();
    }

    /*
     * Register new user
     */
    function insertUser($data)
    {

    	$error = false;
    	$message = ['success' => 'User registered successfull'];

		$this->username   = $data['username']; // please read the below note
		$this->email = $data['email'];
		$this->msisdn = $data['msisdn'];
		$this->active = 1; // New users are registered
		$this->credits = $data['credits'];
		$this->date    = time();

        if (!$error) {
        	$this->db->insert('users', $this);
            $message['user_id'] = $this->db->insert_id();
        }

        return $message;
    }

    /*
     * Update user information
     */
    function updateUser($data)
    {
    	$error = false;
    	$message = ['success' => 'User information updated'];

        $this->username   = $data['username'];
        $this->email = $data['email'];
        $this->mobile = $data['mobile'];
        $this->credits = $data['credits'];
        $this->active = (isset($data['active'])) ? 1 : 0;
        $this->date    = time();

        if ($error || !$this->db->update('users', $this, array('id' => $data['id']))) {
        	$message = ['error' => 'Error updating information. Please, try again.'];
        }
        return $message;
    }

    /*
     * Update user information
     */
    function updateUserCredits($id, $credits)
    {
    	$this->credits = $credits;
        $this->date    = time();

        $this->db->update('users', $this, array('id' => $id));
    }

    /*
     * Remove subscription of user
     */
    function updateSubscriptionStatus($msisdn, $active)
    {
    	$this->active = $active;
		$this->db->update('users', $this, array('msisdn' => $msisdn));

        $this->load->model('Register');
        $this->Register->createRegister(array(
        		'entity'   => 'users',
        		'fk_id'	   => $msisdn,
        		'log_type' => 'Update subscription',
        		'value'	   => ($active == '1') ? 'Active' : 'Disabled'
        	)
		);
    }

    /*
     * Remove user from database
     */
    function deleteUser($msisdn)
    {
        $this->db->delete('users', array('msisdn' => $msisdn));

        $this->load->model('Register');
        $this->Register->createRegister(array(
                'entity'   => 'users',
                'fk_id'    => $msisdn,
                'log_type' => 'Delete user',
                'value'    => 'No founds'
            )
        );
    }

}