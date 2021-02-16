<?php

namespace com\swayam\ocr\porua\service;

use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManager;
use com\swayam\ocr\porua\model\UserDetails;
use com\swayam\ocr\porua\model\UserRole;

require_once __DIR__ . '/UserService.php';
require_once __DIR__ . '/../model/UserDetails.php';
require_once __DIR__ . '/../model/UserRole.php';

/**
 *
 * @author paawak
 */
class UserServiceImpl implements UserService {

    private LoggerInterface $logger;
    private EntityManager $entityManager;

    public function __construct(LoggerInterface $logger, EntityManager $entityManager) {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
    }

    public function fetchExistingUser(array $payload): UserDetails {
        $userDetails = $this->entityManager->getRepository(UserDetails::class)->findOneBy(array(
            'email' => $payload['email']
        ));
        return $userDetails;
    }

    public function registerNewUser(array $payload): UserDetails {
        $userDetails = new UserDetails();
        $userDetails->setName($payload['name']);
        $userDetails->setEmail($payload['email']);
        $userDetails->setRole(UserRole::CORRECTION_ROLE);
        $this->entityManager->persist($userDetails);
        $this->entityManager->flush();
        $this->logger->info("Registered a New User:", array($userDetails));
        return $userDetails;
    }

}
