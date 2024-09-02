<?php

namespace App\Http\ExceptionHandle;

Trait ExceptionHandle
{

    public function handle($throwable)
    {
        report($throwable);

        return $throwable->getMessage();
    }
}
