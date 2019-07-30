<?php
namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Contact 
{
    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=100)
     */
    private $firstname;
    
    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=100)
     */
    private $lastname;
    
    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Regex(
     *  pattern="/[0-9]{10}/"
     * )
     */
    private $phone;
    
    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Email()
     * )
     */
    private $email;
    
    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(min=10)
     * )
     */
    private $message;
    
    /**
     * @var Resource|null
     */
    private $resource;
    
    /**
     * @return resource|null
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param resource|null $resource
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
    }

    /**
     * @return string|null
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @return string|null
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @return string|null
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param null|string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @param null|string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @param null|string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @param null|string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param null|string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }
}