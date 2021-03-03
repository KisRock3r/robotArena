<?php

namespace App\Entity;

use App\Repository\RobotRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RobotRepository::class)
 */
class Robot
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     */
    private $name;
    /**
     * @ORM\Column(type="string")
     */
    private $type;
    /**
     * @ORM\Column(type="smallint")
     */
    private $power;
    
    //Getters
    public function getId()
    {
        return $this->id;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getType()
    {
        return $this->type;
    }
    public function getPower()
    {
        return $this->power;
    }
    //Setters
    public function setName($name)
    {
        $this->name = $name;
    }
    public function setType($type)
    {
        $this->type = $type;
    }
    public function setPower($power)
    {
        $this->power = $power;
    }
}
