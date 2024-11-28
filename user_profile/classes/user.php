<?php

class User {
    public $userID;
    public $firstName;
    public $lastName;
    public $email;
    public $phoneNumber;
    public $street;
    public $postalCode;
    public $city;

    public function __construct($data) {
        $this->userID = $data['userID'];
        $this->firstName = htmlspecialchars($data['firstName']);
        $this->lastName = htmlspecialchars($data['lastName']);
        $this->email = htmlspecialchars($data['email']);
        $this->phoneNumber = htmlspecialchars($data['phoneNumber']);
        $this->street = htmlspecialchars($data['street']);
        $this->postalCode = htmlspecialchars($data['postalCode']);
        $this->city = htmlspecialchars($data['city']);
    }
}
?>
