<?php
namespace Admin\Model;

class User
{

    public $user_id;

    public $firstname;

    public $lastname;

    public $username;

    public $email;

    public $password;

    public $avatar;

    public $status;

    /**
     * Encode password into md5
     * 
     * @param string $clear_password            
     */
    public function setPassword($clear_password)
    {
        $this->password = md5($clear_password);
    }

    /**
     * maps user data
     * 
     * @param array $data            
     */
    function exchangeArray($data)
    {
        $this->lastname = (isset($data['lastname'])) ? $data['lastname'] : null;
        if (isset($data["password"])) {
            $this->setPassword($data["password"]);
        }
    }
}