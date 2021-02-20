<?php

namespace com\swayam\ocr\porua\repo;

use \com\swayam\ocr\porua\model\CorrectedWord;
use \com\swayam\ocr\porua\model\OcrWord;
use \com\swayam\ocr\porua\model\UserDetails;

/**
 *
 * @author paawak
 */
interface CorrectedWordRepository {

    function getCorrectedWord(OcrWord $ocrWord, UserDetails $user): ?CorrectedWord;

    function save(CorrectedWord $entity): CorrectedWord;

    function updateCorrectedText(OcrWord $ocrWord, string $correctedText, UserDetails $user): int;

    function markAsIgnored(OcrWord $ocrWord, UserDetails $user): int;
    
}
