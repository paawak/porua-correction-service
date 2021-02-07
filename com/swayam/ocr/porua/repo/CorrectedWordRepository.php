<?php

namespace com\swayam\ocr\porua\repo;

use \com\swayam\ocr\porua\model\CorrectedWord;
use \com\swayam\ocr\porua\model\UserDetails;

/**
 *
 * @author paawak
 */
interface CorrectedWordRepository {

    function getCorrectedWord(int $ocrWordId, UserDetails $user): ?CorrectedWord;

    function save(CorrectedWord $entity): CorrectedWord;

    function updateCorrectedText(int $ocrWordId, string $correctedText, UserDetails $user): int;

    function markAsIgnored(int $ocrWordId, UserDetails $user): int;
    
}
