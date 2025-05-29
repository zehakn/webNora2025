<?php
require_once __DIR__ . '/BaseDao.php';


class AuthDao extends BaseDao {
   protected $table_name;


   public function __construct() {
       $this->table_name = "user";
       parent::__construct($this->table_name);
   }


   public function get_user_by_email($email) {
       // Replace query_unique with getByField which is available in BaseDAO
       return $this->getByField('Email', $email); 
   }
   
   public function get_user_by_username($username) {
       return $this->getByField('Username', $username); 
   }
}
