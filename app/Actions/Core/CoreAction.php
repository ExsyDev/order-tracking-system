<?php

namespace App\Actions\Core;

use App\Contracts\ActionContract;
use Exception;

abstract class CoreAction implements ActionContract
{
    /**
     * Execute action
     * @param ...$params
     * @return mixed|null
     * @throws Exception
     */
    public function execute(...$params): mixed
    {
        if (!method_exists($this, 'handle')) {
            throw new Exception('Method "handle" does not exist in: ' . get_class($this));
        }

        return $this->handle(...$params);
    }
}
