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

require_once __DIR__ . '/OcrWordService.php';
require_once __DIR__ . '/../repo/OcrWordRepositoryImpl.php';
require_once __DIR__ . '/../repo/CorrectedWordRepositoryImpl.php';

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

    public function getWords(int $bookId, int $pageImageId): array {
        return $this->getOcrWordRepository()->getWordsInPage($bookId, $pageImageId);
    }

    public function markWordAsIgnored(int $ocrWordId, UserDetails $user): int {
        
    }

    public function updateCorrectTextInOcrWord(int $ocrWordId, string $correctedText, UserDetails $user): int {
        
    }
    
    private function getOcrWordRepository(): OcrWordRepository {
        return new OcrWordRepositoryImpl($this->entityManager);
    }
    
    private function getCorrectedWordRepository(): CorrectedWordRepository {
        return new CorrectedWordRepositoryImpl($this->entityManager);
    }

}
