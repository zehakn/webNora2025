<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/AuthDao.php';
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../data/roles.php'; 

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthService extends BaseService {
   private $auth_dao;
   
   public function __construct() {
       $this->auth_dao = new AuthDao();
       parent::__construct(new AuthDao());
   }

   public function get_user_by_email($email){
       return $this->auth_dao->get_user_by_email($email);
   }

   public function register($data) {
       try {
           if (empty($data['email']) || empty($data['password'])) {
               return ['success' => false, 'error' => 'Email and password are required.'];
           }
           
           // Check if user with email already exists
           $existingUser = $this->auth_dao->get_user_by_email($data['email']);
           if ($existingUser) {
               return [
                   'success' => false,
                   'error' => 'User with this email already exists'
               ];
           }
           
           // Check if user with username already exists
           if (isset($data['username']) && !empty($data['username'])) {
               $existingUsername = $this->auth_dao->get_user_by_username($data['username']);
               if ($existingUsername) {
                   return [
                       'success' => false,
                       'error' => 'User with this username already exists'
                   ];
               }
           }

           // Create user data array with full information
           $userData = [
               'email' => $data['email'],
               'password' => password_hash($data['password'], PASSWORD_BCRYPT),
               'fullName' => $data['fullName'] ?? null, 
               'username' => $data['username'] ?? null, 
               'CreatedAt' => date('Y-m-d H:i:s')
           ];

           $userId = parent::create($userData);
           
           // Return user data without the password
           return [
               'success' => true,
               'data' => [
                   'id' => $userId,
                   'email' => $data['email'],
                   'fullName' => $data['fullName'] ?? null,
                   'username' => $data['username'] ?? null
               ]
           ];
       } catch (Exception $e) {
           return [
               'success' => false,
               'error' => $e->getMessage()
           ];
       }
   }

   public function login($data) {
       try {
           if (empty($data['username']) || empty($data['password'])) {
               return ['success' => false, 'error' => 'Username and password are required.'];
           }
           
           $user = $this->auth_dao->get_user_by_username($data['username']);

           if (!$user) {
               return [
                   'success' => false,
                   'error' => 'Invalid username or password'
               ];
           }

           // Check if password column is capitalized or lowercase
           $passwordField = isset($user['Password']) ? 'Password' : 'password';
           
           if (password_verify($data['password'], $user[$passwordField])) { 
               unset($user[$passwordField]); 
               
               // Determine role based on username
               $username = $user['Username'];
               $role = ($username === 'zehaknora') ? Roles::ADMIN : Roles::USER;
               $user['role'] = $role;
               
               // Generate JWT token
               $jwt_payload = [
                   'user' => $user,
                   'iat' => time(),
                   'exp' => time() + (60 * 60 * 24) 
               ];

              
               $token = JWT::encode(
                   $jwt_payload,
                   Config::JWT_SECRET(),
                   'HS256'
               );
               
               return [
                   'success' => true,
                   'data' => array_merge($user, ['token' => $token])
               ];
           } else {
               return [
                   'success' => false,
                   'error' => 'Invalid username or password'
               ];
           }
       } catch (Exception $e) {
           return [
               'success' => false,
               'error' => $e->getMessage()
           ];
       }
   }
}
?>
