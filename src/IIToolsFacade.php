<?php

namespace Immersioninteractive\ToolsController;

use Illuminate\Support\Facades\Facade;

class IIToolsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'IITools';
    }
}
