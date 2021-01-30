<?php

namespace com\swayam\ocr\porua\service;

use Psr\Log\LoggerInterface;
use com\swayam\ocr\porua\model\UserDetails;

require_once __DIR__ . '/UserService.php';
require_once __DIR__ . '/../model/UserDetails.php';

/**
 *
 * @author paawak
 */
class UserServiceImpl implements UserService {

    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }

    public function fetchExistingUser(array $payload): UserDetails {
        $this->logger->info("PAYLOAD", $payload);
        $userDetails = new UserDetails();
        $userDetails->setEmail("DUMMY");
        $userDetails->setId(-1);
        $userDetails->setName("Dummy");
        return $userDetails;
    }

    public function registerNewUser(array $payload): UserDetails {
        return null;
    }

}
