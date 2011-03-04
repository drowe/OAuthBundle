<?php

namespace OnePlusOne\OAuthBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class OAuthBundle extends Bundle
{
    public function getNamespace()
    {
        return __NAMESPACE__;
    }

    public function getPath()
    {
        return strtr(__DIR__, '\\', '/');
    }
}
