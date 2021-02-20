<?php

namespace com\swayam\ocr\porua\service;

use com\swayam\ocr\porua\model\UserDetails;

/**
 *
 * @author paawak
 */
interface UserService {
    
    const USER_DETAILS = 'USER_DETAILS';
        
    function fetchExistingUser(array $payload): ?UserDetails;
    
    function registerNewUser(array $payload): UserDetails;
    
}
