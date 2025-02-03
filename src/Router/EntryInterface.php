<?php

namespace Simp\Router\Router;

interface EntryInterface
{
    /**
     * Controller entry.
     * @param ...$args
     * @return mixed
     */
    public function entry(...$args): mixed;
}