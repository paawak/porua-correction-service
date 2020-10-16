<?php

namespace com\swayam\ocr\porua\rest;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Fig\Http\Message\StatusCodeInterface;
use \Slim\Psr7\Headers;

class RequestInterceptingMiddleware {

    const AUTH_HEADER_NAME = 'Authorization';
    const CLIENT_ID = '955630342713-55eu6b3k5hmsg8grojjmk8mj1gi47g37.apps.googleusercontent.com';

    private $container;
    private $logger;

    public function __construct(ContainerInterface $container, LoggerInterface $logger) {
        $this->container = $container;
        $this->logger = $logger;
    }

    public function __invoke(Request $request, RequestHandler $handler): Response {
        $this->logger->info("Handling request of type: " . $request->getMethod() . ", with target-uri: " . $request->getRequestTarget());
        
        if ($request->getRequestTarget() === '/') {
            return $handler->handle($request);
        }
        
        if (!$request->hasHeader(self::AUTH_HEADER_NAME)) {
            return $this->getNotAuthorizedResponse('Could not find authorization token in the header');
        }

        $idToken = $request->getHeader(self::AUTH_HEADER_NAME)[0];

        $this->logger->debug('IdToken from OAuth2: ' . $idToken);

        $client = new \Google_Client(['client_id' => self::CLIENT_ID]);
        $payload = $client->verifyIdToken($idToken);
        if ($payload) {
            $this->logger->debug('Payload: ', $payload);
        } else {
            return $this->getNotAuthorizedResponse('Could not authenticate');
        }
        
        //call the actual handler now that this is authenticated
        $response = $handler->handle($request);
        
        //add CORS before returning
        return $this->addCORSHeaders($response);
    }

    private function getNotAuthorizedResponse(string $reasonPhrase): Response {
        $this->logger->warning('Not authorised: ' . $reasonPhrase);
        $headers = new Headers();
        $headers->addHeader('Content-Type', 'application/json');
        $response = new Response(StatusCodeInterface::STATUS_UNAUTHORIZED, $headers);

        $jsonPayload = json_encode(['httpStatusCode'=> StatusCodeInterface::STATUS_UNAUTHORIZED, 'error' => $reasonPhrase], JSON_PRETTY_PRINT);
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
