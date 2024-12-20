<?php

namespace App\Contracts;

/**
 * Execute action
 * @param array $params
 * @return mixed
 */
interface ActionContract
{
    /**
     * Execute action
     * @param array $params
     * @return mixed
     */
    public function execute(...$params);
}
