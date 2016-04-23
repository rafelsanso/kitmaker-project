<?php if (! defined('BASEPATH')) exit('No direct script access');

class Register extends CI_Model {

    function __construct() {

        parent::__construct();

        $id 		= null;
        $entity 	= null;
        $fk_id 		= null;
        $log_type 	= null;
        $value      = null;
        $date 	    = null;

    }

    /*
     * Lists a number of users
     */
    function getRegisterType($type)
    {
        $query = $this->db->get_where('registers', array('$type' => $type));
        return $query->result();
    }

    /*
     * Create new register
     */
    function createRegister($data)
    {
        $this->entity = $data['entity'];
        $this->fk_id = $data['fk_id'];
        $this->log_type = $data['log_type'];
        $this->value = $data['value'];
        $this->date    = time();

        $this->db->insert('registers', $this);
    }
}