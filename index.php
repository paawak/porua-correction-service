<?php

require __DIR__ . '/com/swayam/ocr/porua/rest/IndexController.php';
require __DIR__ . '/com/swayam/ocr/porua/rest/OCRQueryController.php';
require __DIR__ . '/com/swayam/ocr/porua/rest/OCRCorrectionController.php';
require __DIR__ . '/com/swayam/ocr/porua/rest/RequestInterceptingMiddleware.php';

use DI\Bridge\Slim\Bridge;
use Slim\Handlers\ErrorHandler;
use Slim\Factory\ServerRequestCreatorFactory;
use Psr\Log\LoggerInterface;
use com\swayam\ocr\porua\rest\IndexController;
use com\swayam\ocr\porua\rest\OCRQueryController;
use com\swayam\ocr\porua\rest\OCRCorrectionController;
use com\swayam\ocr\porua\rest\RequestInterceptingMiddleware;

$container = require __DIR__ . '/com/swayam/ocr/porua/config/DIContainerBootstrap.php';

$app = Bridge::create($container);

$logger = $container->get(LoggerInterface::class);

$callableResolver = $app->getCallableResolver();
$responseFactory = $app->getResponseFactory();
$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();
$errorHandler = new ErrorHandler($callableResolver, $responseFactory, $logger);

$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler($errorHandler);

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add($container->get(RequestInterceptingMiddleware::class));

$app->get('/', [IndexController::class, 'get']);

$app->get('/ocr/train/query/book', [OCRQueryController::class, 'getAllBooks']);
$app->get('/ocr/train/query/book/{bookId}/page-count', [OCRQueryController::class, 'getPageCountInBook']);
$app->get('/ocr/train/query/page', [OCRQueryController::class, 'getPagesInBook']);
$app->get('/ocr/train/query/word', [OCRQueryController::class, 'getWordsInPage']);
$app->get('/ocr/train/query/word/image', [OCRQueryController::class, 'getWordImage']);

$app->put('/ocr/train/correction/page/ignore/{pageImageId}', [OCRCorrectionController::class, 'markPageAsIgnored']);
$app->put('/ocr/train/correction/page/complete/{pageImageId}', [OCRCorrectionController::class, 'markPageAsCompleted']);
$app->post('/ocr/train/correction/word', [OCRCorrectionController::class, 'applyCorrectionToOcrWords']);
$app->post('/ocr/train/correction/word/ignore', [OCRCorrectionController::class, 'markOcrWordsAsIgnored']);

$app->run();
?>
