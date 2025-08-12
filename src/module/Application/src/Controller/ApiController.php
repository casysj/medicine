<?php

namespace Application\Controller;

use InvalidArgumentException;
use JsonException;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Stdlib\ResponseInterface;
use RuntimeException;
use Throwable;

class ApiController extends AbstractActionController
{
  public function createSuccessResponse($data = null, string $message = 'Success', int $statusCode = 200): ResponseInterface
  {
    /** @var Response $response */
    $response = $this->getResponse();
    $response->setStatusCode($statusCode);
    $response->getHeaders()->addHeaderLine('Content-Type', 'application/json');
    $responseData = [
      'success' => true,
      'message' => $message
    ];

    if ($data !== null) {
      $responseData['data'] = $data;
    }
    $response->setContent(json_encode($responseData));

    return $response;
  }

  public function createErrorResponse(string $message, int $statusCode = 400, array $errors = []): ResponseInterface
  {
    /** @var Response $response */
    $response = $this->getResponse();
    $response->setStatusCode($statusCode);
    $response->getHeaders()->addHeaderLine('Content-Type', 'application/json');
    $responseData = [
      'success' => false,
      'message' => $message
    ];
    if (! empty($errors)) {
      $responseData['errors'] = $errors;
    }

    $response->setContent(json_encode($responseData));

    return $response;
  }

  public function handleException(Throwable $exception, string $message = '')
  {
    if ($exception instanceof InvalidArgumentException || $exception instanceof RuntimeException) {
      return $this->createErrorResponse($message, 400, [$exception->getMessage()]);
    }
    return $this->createErrorResponse('Internal server error : '. $exception->getMessage(), 500);
  }

  public function methodNotAllowed(string $message, array $errors = [])
  {
    $response = $this->createErrorResponse($message, 405, $errors);
    $response->getHeaders()->addHeaderLine('Content-Type', 'application/json');
    return $response;
  }
}
