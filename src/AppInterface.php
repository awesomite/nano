<?php

namespace Awesomite\Nano;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface AppInterface
{
    public function run(Request $request = null, bool $autoFlush = true): Response;
}
