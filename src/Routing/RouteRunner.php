<?php
declare(strict_types=1);

namespace Ody\Core\Routing;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ody\Core\Exception\HttpMethodNotAllowedException;
use Ody\Core\Exception\HttpNotFoundException;
use Ody\Core\Interfaces\RouteCollectorProxyInterface;
use Ody\Core\Interfaces\RouteParserInterface;
use Ody\Core\Interfaces\RouteResolverInterface;
use Ody\Core\Middleware\RoutingMiddleware;

class RouteRunner implements RequestHandlerInterface
{
    private RouteResolverInterface $routeResolver;

    private RouteParserInterface $routeParser;

    /**
     * @var RouteCollectorProxyInterface<\Psr\Container\ContainerInterface|null>
     */
    private ?RouteCollectorProxyInterface $routeCollectorProxy;

    /**
     * @param RouteCollectorProxyInterface<\Psr\Container\ContainerInterface|null> $routeCollectorProxy
     */
    public function __construct(
        RouteResolverInterface $routeResolver,
        RouteParserInterface $routeParser,
        ?RouteCollectorProxyInterface $routeCollectorProxy = null
    ) {
        $this->routeResolver = $routeResolver;
        $this->routeParser = $routeParser;
        $this->routeCollectorProxy = $routeCollectorProxy;
    }

    /**
     * This request handler is instantiated automatically in App::__construct()
     * It is at the very tip of the middleware queue meaning it will be executed
     * last and it detects whether or not routing has been performed in the user
     * defined middleware stack. In the event that the user did not perform routing
     * it is done here
     *
     * @throws HttpNotFoundException
     * @throws HttpMethodNotAllowedException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // If routing hasn't been done, then do it now so we can dispatch
        if ($request->getAttribute(RouteContext::ROUTING_RESULTS) === null) {
            $routingMiddleware = new RoutingMiddleware($this->routeResolver, $this->routeParser);
            $request = $routingMiddleware->performRouting($request);
        }

        if ($this->routeCollectorProxy !== null) {
            $request = $request->withAttribute(
                RouteContext::BASE_PATH,
                $this->routeCollectorProxy->getBasePath()
            );
        }

        /** @var Route<\Psr\Container\ContainerInterface|null> $route */
        $route = $request->getAttribute(RouteContext::ROUTE);
        return $route->run($request);
    }
}
