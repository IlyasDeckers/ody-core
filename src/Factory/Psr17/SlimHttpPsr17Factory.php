<?php
declare(strict_types=1);

namespace Ody\Core\Factory\Psr17;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use RuntimeException;

class SlimHttpPsr17Factory extends Psr17Factory
{
    protected static string $responseFactoryClass = 'Ody\Core\Http\Factory\DecoratedResponseFactory';

    /**
     * @throws RuntimeException when the factory could not be instantiated
     */
    public static function createDecoratedResponseFactory(
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface $streamFactory
    ): ResponseFactoryInterface {
        if (
            !((
                $decoratedResponseFactory = new static::$responseFactoryClass($responseFactory, $streamFactory)
                ) instanceof ResponseFactoryInterface
            )
        ) {
            throw new RuntimeException(get_called_class() . ' could not instantiate a decorated response factory.');
        }

        return $decoratedResponseFactory;
    }
}
