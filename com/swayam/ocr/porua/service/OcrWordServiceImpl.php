<?php

namespace com\swayam\ocr\porua\service;

use Doctrine\ORM\EntityManager;
use \com\swayam\ocr\porua\model\UserDetails;
use \com\swayam\ocr\porua\model\OcrWordId;
use \com\swayam\ocr\porua\model\OcrWord;
use \com\swayam\ocr\porua\repo\OcrWordRepository;
use \com\swayam\ocr\porua\repo\CorrectedWordRepository;
use \com\swayam\ocr\porua\repo\OcrWordRepositoryImpl;
use \com\swayam\ocr\porua\repo\CorrectedWordRepositoryImpl;

/**
 *
 * @author paawak
 */
class OcrWordServiceImpl implements OcrWordService {

    private $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function getWord(OcrWordId $ocrWordId): OcrWord {
        return $this->getOcrWordRepository()->getWord($ocrWordId);
    }

    public function getWords(integer $bookId, integer $pageImageId): array {
        return $this->getOcrWordRepository()->getWordsInPage($bookId, $pageImageId);
    }

    public function markWordAsIgnored(integer $ocrWordId, UserDetails $user): integer {
        
    }

    public function updateCorrectTextInOcrWord(integer $ocrWordId, string $correctedText, UserDetails $user): integer {
        
    }
    
    private function getOcrWordRepository(): OcrWordRepository {
        return new OcrWordRepositoryImpl($entityManager);
    }
    
    private function getCorrectedWordRepository(): CorrectedWordRepository {
        return new CorrectedWordRepositoryImpl($entityManager);
    }

}
