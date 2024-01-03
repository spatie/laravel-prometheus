<?php

namespace Spatie\Prometheus\Tests\TestSupport\Actions;

use Spatie\Prometheus\Actions\RenderCollectorsAction;

class TestRenderCollectorsAction extends RenderCollectorsAction
{
    protected function renderRegistry(): string
    {
        $result = "# TestRenderCollectorsAction\n";

        $result .= parent::renderRegistry();

        return $result;
    }
}
