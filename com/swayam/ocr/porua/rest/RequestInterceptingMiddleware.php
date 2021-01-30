<?php

namespace com\swayam\ocr\porua\rest;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Fig\Http\Message\StatusCodeInterface;
use \Slim\Psr7\Headers;
use com\swayam\ocr\porua\service\UserService;

class RequestInterceptingMiddleware {

    private const URL_IMAGE_FETCH = '/ocr/train/query/word/image';
    private const URL_REGISTRATION = '/ocr/train/user/register';
    private const AUTH_HEADER_NAME = 'Authorization';
    private const CLIENT_ID = '955630342713-55eu6b3k5hmsg8grojjmk8mj1gi47g37.apps.googleusercontent.com';

    private LoggerInterface $logger;
    private UserService $userService;

    public function __construct(ContainerInterface $container, LoggerInterface $logger, UserService $userService) {
        $this->container = $container;
        $this->logger = $logger;
        $this->userService = $userService;
    }

    public function __invoke(ServerRequestInterface $request, RequestHandler $handler): Response {
        $this->logger->info("Handling request of type: " . $request->getMethod() . ", with target-uri: " . $request->getRequestTarget());

        if ($request->getRequestTarget() === '/') {
            return $handler->handle($request);
        }

        if ($request->getMethod() === 'OPTIONS') {
            return $this->addCORSHeaders(new Response());
        }

        $idToken = null;

        if (strstr($request->getRequestTarget(), self::URL_IMAGE_FETCH)) {
            $idToken = $this->getAuthFromGetRequest($request);
        } else {
            $idToken = $this->getAuthFromHeaders($request);
        }

        if (!$idToken) {
            return $this->getNotAuthorizedResponse('Could not find authorization token');
        }

        $this->logger->debug('IdToken from OAuth2: ' . $idToken);

        $payload = $this->verifyGoogleTokenAndExtractToken($idToken);

        if (!$payload) {
            return $this->getNotAuthorizedResponse('Could not authenticate');
        }

        $userDetails = $this->userService->fetchExistingUser($payload);

        if (!$userDetails && strstr($request->getRequestTarget(), self::URL_REGISTRATION)) {
            $userDetails = $this->userService->registerNewUser($payload);
        }

        if (!$userDetails) {
            return $this->getNotAuthorizedResponse('Error registering user');
        }

        $requestWithAttribute = $request->withAttribute(UserService::USER_DETAILS, $userDetails);

        //call the actual handler now that this is authenticated
        $response = $handler->handle($requestWithAttribute);

        //add CORS before returning
        return $this->addCORSHeaders($response);
    }

    private function verifyGoogleTokenAndExtractToken(string $idToken): array {
        $client = new \Google_Client(['client_id' => self::CLIENT_ID]);
        $payload = $client->verifyIdToken($idToken);
        if ($payload) {
            $this->logger->debug('Payload: ', $payload);
            return $payload;
        } else {
            $this->logger->info('User idToken not valid');
            return null;
        }
    }

    private function getAuthFromHeaders(ServerRequestInterface $request): string {
        if (!$request->hasHeader(self::AUTH_HEADER_NAME)) {
            return null;
        }
        return $request->getHeader(self::AUTH_HEADER_NAME)[0];
    }

    private function getAuthFromGetRequest(ServerRequestInterface $request): string {
        $queryParams = $request->getQueryParams();
        $this->logger->debug('query params: ', $queryParams);
        return $queryParams[self::AUTH_HEADER_NAME];
    }

    private function getNotAuthorizedResponse(string $reasonPhrase): Response {
        $this->logger->warning('Not authorised: ' . $reasonPhrase);
        $headers = new Headers();
        $headers->addHeader('Content-Type', 'application/json');
        $response = new Response(StatusCodeInterface::STATUS_UNAUTHORIZED, $headers);

        $jsonPayload = json_encode(['httpStatusCode' => StatusCodeInterface::STATUS_UNAUTHORIZED, 'error' => $reasonPhrase], JSON_PRETTY_PRINT);
        $response->getBody()->write($jsonPayload);
        return $response;
    }

    private function addCORSHeaders(Response $response): Response {
        return $response
                        ->withHeader('Access-Control-Allow-Origin', $this->container->get('cors.allow-origin'))
                        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                        ->withHeader('Access-Control-Allow-Credentials', 'true')
                        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    }

}
