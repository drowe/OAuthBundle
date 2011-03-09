<?php

namespace OnePlusOne\OAuthBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class OAuthBundle extends Bundle
{
    public function getNamespace()
    {
        return __NAMESPACE__;
    }
<<<<<<< HEAD
    
    public function getPath()
    {
        return strtr(__DIR__, "\\", "/");
    }
}
=======

    public function getPath()
    {
        return strtr(__DIR__, '\\', '/');
    }
}
>>>>>>> 26aabc6499cdc68773527ae531dad7a6b858c77e
