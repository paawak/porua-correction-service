<?php

namespace com\swayam\ocr\porua\service;

use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use \com\swayam\ocr\porua\model\UserDetails;
use \com\swayam\ocr\porua\model\OcrWordId;
use \com\swayam\ocr\porua\model\OcrWord;
use \com\swayam\ocr\porua\model\CorrectedWord;
use \com\swayam\ocr\porua\model\CorrectedWordEntityTemplate;
use \com\swayam\ocr\porua\dto\OcrWordOutputDto;
use \com\swayam\ocr\porua\repo\OcrWordRepository;
use \com\swayam\ocr\porua\repo\CorrectedWordRepository;
use \com\swayam\ocr\porua\repo\OcrWordRepositoryImpl;
use \com\swayam\ocr\porua\repo\CorrectedWordRepositoryImpl;

require_once __DIR__ . '/OcrWordService.php';
require_once __DIR__ . '/../repo/OcrWordRepositoryImpl.php';
require_once __DIR__ . '/../repo/CorrectedWordRepositoryImpl.php';
require_once __DIR__ . '/../model/CorrectedWordEntityTemplate.php';
require_once __DIR__ . '/../dto/OcrWordOutputDto.php';

/**
 *
 * @author paawak
 */
class OcrWordServiceImpl implements OcrWordService {

    private LoggerInterface $logger;
    private EntityManager $entityManager;

    public function __construct(LoggerInterface $logger, EntityManager $entityManager) {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
    }

    public function getWord(OcrWordId $ocrWordId): OcrWord {
        return $this->getOcrWordRepository()->getWord($ocrWordId);
    }

    public function getWords(int $bookId, int $pageImageId, UserDetails $user): array {
        $ocrWords = $this->getOcrWordRepository()->getWordsInPage($bookId, $pageImageId);
        $toOutputOcrWord = function(OcrWord $ocrWord) {
            $output = new OcrWordOutputDto();
            $output->setConfidence($ocrWord->getConfidence());
            $output->setId($ocrWord->getId());
            $lineNumber = $ocrWord->getLineNumber();
            $output->setLineNumber($lineNumber);
            $output->setOcrWordId($ocrWord->getOcrWordId());
            $output->setRawText($ocrWord->getRawText());
            $output->setX1($ocrWord->getX1());
            $output->setX2($ocrWord->getX2());
            $output->setY1($ocrWord->getY1());
            $output->setY2($ocrWord->getY2());
            //TODO set ignored
            //TODO set corrected text
            $this->logger->debug("********************", array($ocrWord->getId(), $ocrWord->getCorrectedWords()->count()));
            return $output;
        };
        return array_map($toOutputOcrWord, $ocrWords);
    }

    public function markWordAsIgnored(OcrWordId $ocrWordId, UserDetails $user): int {
        $ocrWord = $this->getWord($ocrWordId);
        $existingCorrection = $this->getCorrectedWordRepository()->getCorrectedWord($ocrWord, $user);
        if ($existingCorrection) {
            return $this->getCorrectedWordRepository()->markAsIgnored($ocrWord, $user);
        } else {
            $this->getCorrectedWordRepository()->save($this->toNewEntity($ocrWord, $user, ''));
            return 1;
        }
    }

    public function updateCorrectTextInOcrWord(OcrWordId $ocrWordId, string $correctedText, UserDetails $user): int {
        $ocrWord = $this->getWord($ocrWordId);
        $existingCorrection = $this->getCorrectedWordRepository()->getCorrectedWord($ocrWord, $user);
        if ($existingCorrection) {
            return $this->getCorrectedWordRepository()->updateCorrectedText($ocrWord, $correctedText, $user);
        } else {
            $this->getCorrectedWordRepository()->save($this->toNewEntity($ocrWord, $user, $correctedText));
            return 1;
        }
    }

    private function getOcrWordRepository(): OcrWordRepository {
        return new OcrWordRepositoryImpl($this->entityManager);
    }

    private function getCorrectedWordRepository(): CorrectedWordRepository {
        return new CorrectedWordRepositoryImpl($this->logger, $this->entityManager);
    }

    private function toNewEntity(OcrWord $ocrWord, UserDetails $user, string $correctedText): CorrectedWord {
        $correctedWord = new CorrectedWordEntityTemplate();
        $correctedWord->setOcrWord($ocrWord);
        $correctedWord->setUser($user);
        if ($correctedText) {
            $correctedWord->setCorrectedText($correctedText);
            $correctedWord->setIgnored(false);
        } else {
            $correctedWord->setIgnored(true);
        }
        return $correctedWord;
    }

}
