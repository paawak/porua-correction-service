<?php

namespace com\swayam\ocr\porua\rest;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Fig\Http\Message\StatusCodeInterface;

class AuthenticatingMiddleware {

    const AUTH_HEADER_NAME = 'Authorization';
    const CLIENT_ID = '955630342713-55eu6b3k5hmsg8grojjmk8mj1gi47g37.apps.googleusercontent.com';

    private $logger;

    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }

    public function __invoke(Request $request, RequestHandler $handler): Response {
        if (!$request->hasHeader(self::AUTH_HEADER_NAME)) {
            return $this->notAuthorized('Could not find authorization token in the header');
        }

        $idToken = $request->getHeader(self::AUTH_HEADER_NAME)[0];

        $this->logger->debug('IdToken from OAuth2: ' . $idToken);

        $client = new \Google_Client(['client_id' => self::CLIENT_ID]);
        $payload = $client->verifyIdToken($idToken);
        if ($payload) {
            $this->logger->debug('Payload: ', $payload);
        } else {
            return $this->notAuthorized('Could not authenticate');
        }
        return $handler->handle($request);
    }

    private function notAuthorized(string $reasonPhrase): Response {
        $response = new Response();
        $response->withStatus(StatusCodeInterface::STATUS_UNAUTHORIZED, $reasonPhrase);
        return $response;
    }

}
