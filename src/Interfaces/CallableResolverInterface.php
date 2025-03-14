<?php
declare(strict_types=1);

namespace Ody\Core\Interfaces;

interface CallableResolverInterface
{
    /**
     * Resolve $toResolve into a callable
     *
     * @param string|callable $toResolve
     */
    public function resolve($toResolve): callable;
}