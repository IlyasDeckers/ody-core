<?php
declare(strict_types=1);

namespace Ody\Core\Tests\Mocks;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ody\Core\Interfaces\InvocationStrategyInterface;

class InvocationStrategyTest implements InvocationStrategyInterface
{
    public static $LastCalledFor = null;

    /**
     * Invoke a route callable.
     *
     * @param callable               $callable       The callable to invoke using the strategy.
     * @param ServerRequestInterface $request        The request object.
     * @param ResponseInterface      $response       The response object.
     * @param array                  $routeArguments The route's placeholder arguments
     *
     * @return ResponseInterface The response from the callable.
     */
    public function __invoke(
        callable $callable,
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $routeArguments
    ): ResponseInterface {
        static::$LastCalledFor = $callable;
        return $response;
    }
}
