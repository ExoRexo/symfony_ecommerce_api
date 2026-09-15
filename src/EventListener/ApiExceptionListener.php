<?php

namespace App\EventListener;

use App\Http\ApiResponseFactory;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\ConstraintViolationInterface;

#[AsEventListener(event: KernelEvents::EXCEPTION)]
final readonly class ApiExceptionListener
{
    public function __construct(
        private ApiResponseFactory $responseFactory,
    ) {
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $response = match (true) {
            $exception instanceof ValidationFailedException => $this->responseFactory->failure(
                $this->validationErrors($exception),
                Response::HTTP_BAD_REQUEST,
            ),
            $exception instanceof \JsonException,
            $exception instanceof BadRequestHttpException,
            $exception instanceof \InvalidArgumentException => $this->responseFactory->failure(
                [$this->message($exception, 'Request is invalid')],
                Response::HTTP_BAD_REQUEST,
            ),
            $exception instanceof EntityNotFoundException,
            $exception instanceof NotFoundHttpException => $this->responseFactory->failure(
                [$this->message($exception, 'Resource not found')],
                Response::HTTP_NOT_FOUND,
            ),
            $exception instanceof AuthenticationException => $this->responseFactory->failure(
                [$this->message($exception, 'Authentication failed')],
                Response::HTTP_UNAUTHORIZED,
            ),
            $exception instanceof AccessDeniedException => $this->responseFactory->failure(
                [$this->message($exception, 'Access is denied')],
                Response::HTTP_FORBIDDEN,
            ),
            $exception instanceof HttpExceptionInterface => $this->responseFactory->failure(
                [$this->message($exception, FlattenException::createFromThrowable($exception)->getStatusText())],
                $exception->getStatusCode(),
            ),
            default => $this->responseFactory->failure(
                [$this->message($exception, 'Unexpected server error')],
                Response::HTTP_INTERNAL_SERVER_ERROR,
            ),
        };

        $event->setResponse($response);
    }

    /**
     * @return list<string>
     */
    private function validationErrors(ValidationFailedException $exception): array
    {
        $errors = [];

        foreach ($exception->getViolations() as $violation) {
            \assert($violation instanceof ConstraintViolationInterface);
            $errors[] = sprintf('%s: %s', $violation->getPropertyPath(), $violation->getMessage());
        }

        return $errors === [] ? ['Request is invalid'] : $errors;
    }

    private function message(\Throwable $exception, string $fallback): string
    {
        if (!$this->includeErrorDetails || trim($exception->getMessage()) === '') {
            return $fallback;
        }

        return $exception->getMessage();
    }
}
