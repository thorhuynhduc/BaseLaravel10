<?php

namespace Core\Services;

use Core\Http\ResponseTrait;
use Core\Utils\DateTimeUtil;
use Lucid\Units\Feature;

class BaseFeatures extends Feature
{
    use AuthorizePackageTrait;
    use ResponseTrait;
    use RunInQueue;
    use DateTimeUtil;
    use ServiceSupport;
}
