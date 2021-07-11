<?php

namespace com\swayam\ocr\porua\rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Doctrine\ORM\EntityManager;
use com\swayam\ocr\porua\service\UserService;

/**
 *
 * @author paawak
 */
class UserController {

    private $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function registration(Request $request, Response $response) {
        $userDetails = $request->getAttribute(UserService::USER_DETAILS);
        $payload = json_encode($userDetails, JSON_PRETTY_PRINT);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

}
