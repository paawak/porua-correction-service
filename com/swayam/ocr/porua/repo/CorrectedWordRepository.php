<?php

namespace com\swayam\ocr\porua\repo;

use \com\swayam\ocr\porua\model\CorrectedWord;
use \com\swayam\ocr\porua\model\UserDetails;

/**
 *
 * @author paawak
 */
interface CorrectedWordRepository {

    function getCorrectedWord(integer $ocrWordId, UserDetails $user): CorrectedWord;

    function save(CorrectedWord $entity): CorrectedWord;

    function updateCorrectedText(integer $ocrWordId, string $correctedText, UserDetails $user): integer;

    function markAsIgnored(integer $ocrWordId, UserDetails $user): integer;
}
