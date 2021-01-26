<?php

namespace com\swayam\ocr\porua\rest;

use \Exception as Exception;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use com\swayam\ocr\porua\model\PageImage;
use com\swayam\ocr\porua\model\OcrWord;
use \com\swayam\ocr\porua\model\OcrWordId;
use com\swayam\ocr\porua\dto\OcrCorrectionDto;
use com\swayam\ocr\porua\service\OcrWordService;

require_once __DIR__ . '/../model/PageImage.php';
require_once __DIR__ . '/../model/OcrWord.php';
require_once __DIR__ . '/../model/OcrWordId.php';
require_once __DIR__ . '/../dto/OcrCorrectionDto.php';

class OCRCorrectionController {

    private $entityManager;
    private $ocrWordService;

    public function __construct(EntityManager $entityManager, OcrWordService $ocrWordService) {
        $this->entityManager = $entityManager;
        $this->ocrWordService = $ocrWordService;
    }

    public function markPageAsIgnored(Request $request, Response $response, $pageImageId) {
        $sql = "UPDATE " . PageImage::class . " page SET page.ignored = TRUE WHERE page.id = :pageImageId";
        $updateQuery = $this->entityManager->createQuery($sql);
        $updated = $updateQuery->execute(array(
            'pageImageId' => $pageImageId
        ));

        $response->getBody()->write("$updated");
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function markPageAsCompleted(Request $request, Response $response, $pageImageId) {
        $sql = "UPDATE " . PageImage::class . " page SET page.correctionCompleted = TRUE WHERE page.id = :pageImageId";
        $updateQuery = $this->entityManager->createQuery($sql);
        $updated = $updateQuery->execute(array(
            'pageImageId' => $pageImageId
        ));

        $response->getBody()->write("$updated");
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function applyCorrectionToOcrWords(Request $request, Response $response) {
        $rawOcrCorrectionDtoAsArray = $request->getParsedBody();

        if (!is_array($rawOcrCorrectionDtoAsArray)) {
            throw new Exception('Invalid body: Could not decode JSON');
        }

        $updatedList = array();

        foreach ($rawOcrCorrectionDtoAsArray as $ocrCorrectionDtoAsArray) {
            $ocrCorrectionDto = OcrCorrectionDto::fromJsonArray($ocrCorrectionDtoAsArray);
            $updated = 0;//$this->ocrWordService->updateCorrectTextInOcrWord($ocrCorrectionDto->getOcrWordId(), $correctedText, $user)
            array_push($updatedList, $updated);
        }

        $payload = json_encode($updatedList, JSON_PRETTY_PRINT);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function markOcrWordsAsIgnored(Request $request, Response $response) {
        $rawOcrWordIDsAsArray = $request->getParsedBody();

        if (!is_array($rawOcrWordIDsAsArray)) {
            throw new Exception('Invalid body: Could not decode JSON');
        }

        $updatedList = array();

        foreach ($rawOcrWordIDsAsArray as $ocrWordIdAsArray) {
            $ocrWordId = OcrWordId::fromJsonArray($ocrWordIdAsArray);
            $updated = 0;// $this->ocrWordService->markWordAsIgnored($ocrWordId, $user);
            array_push($updatedList, $updated);
        }

        $payload = json_encode($updatedList, JSON_PRETTY_PRINT);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

}
