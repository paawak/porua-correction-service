<?php

namespace com\swayam\ocr\porua\rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Doctrine\ORM\EntityManager;
use com\swayam\ocr\porua\model\UserDetails;

require_once __DIR__ . '/../model/UserDetails.php';

/**
 *
 * @author paawak
 */
class UserController {

    private $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function register(Request $request, Response $response) {
        $userDetails = new UserDetails();
        $userDetails->setEmail("DUMMY");
        $userDetails->setId(-1);
        $userDetails->setName("Dummy");
        $payload = json_encode($userDetails, JSON_PRETTY_PRINT);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

}
