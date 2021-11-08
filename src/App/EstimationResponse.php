<?php

namespace App;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class EstimationResponse
{
    private $message;
    private $prix;

    public function __construct($message="",$prix=-1)
    {
        $this->message = $message;
        $this->prix = $prix;
    }

    //getMessage : return the message
    public function getMessage()
    {
        return $this->message;
    }

    //getPrix : return the price
    public function getPrix()
    {
        return $this->prix;
    }

    //setMessage : set the message
    public function setMessage($message)
    {
        $this->message = $message;
    }

    //setPrix : set the price
    public function setPrix($prix)
    {
        $this->prix = $prix;
    }

    //toJson : put the info of this class into json
    public function toJson()
    {
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $data = $serializer->serialize($this, 'json');
        return $data;
    }
}
