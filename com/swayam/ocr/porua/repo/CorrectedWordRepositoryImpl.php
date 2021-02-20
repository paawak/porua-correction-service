<?php

namespace com\swayam\ocr\porua\repo;

use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Doctrine\Persistence\ObjectRepository;
use \com\swayam\ocr\porua\model\CorrectedWord;
use \com\swayam\ocr\porua\model\OcrWord;
use com\swayam\ocr\porua\model\CorrectedWordEntityTemplate;
use \com\swayam\ocr\porua\model\UserDetails;

require_once __DIR__ . '/CorrectedWordRepository.php';

/**
 *
 * @author paawak
 */
class CorrectedWordRepositoryImpl implements CorrectedWordRepository {

    private LoggerInterface $logger;
    private EntityManager $entityManager;

    public function __construct(LoggerInterface $logger, EntityManager $entityManager) {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
    }

    public function getCorrectedWord(OcrWord $ocrWord, UserDetails $user): ?CorrectedWord {
        $correctedWord = $this->getRepository()->findOneBy(array(
            'ocrWord' => $ocrWord,
            'user' => $user
        ));
        return $correctedWord;
    }

    public function save(CorrectedWord $entity): CorrectedWord {
        $this->logger->info("Entity:", [$entity]);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        return $entity;
    }

    public function updateCorrectedText(OcrWord $ocrWord, string $correctedText, UserDetails $user): int {
        $sql = "UPDATE " . CorrectedWordEntityTemplate::class . " word SET word.correctedText = :correctedText WHERE word.ocrWord = :ocrWord AND word.user = :user";
        $updateQuery = $this->entityManager->createQuery($sql);
        $updated = $updateQuery->execute(array(
            'ocrWord' => $ocrWord,
            'correctedText' => $correctedText,
            'user' => $user
        ));
        return $updated;
    }

    public function markAsIgnored(OcrWord $ocrWord, UserDetails $user): int {
        $sql = "UPDATE " . CorrectedWordEntityTemplate::class . " word SET word.ignored = TRUE WHERE word.ocrWord = :ocrWord AND word.user = :user";
        $updateQuery = $this->entityManager->createQuery($sql);
        $updated = $updateQuery->execute(array(
            'ocrWord' => $ocrWord,
            'user' => $user
        ));
        return $updated;
    }

    private function getRepository(): ObjectRepository {
        return $this->entityManager->getRepository(CorrectedWordEntityTemplate::class);
    }

}
