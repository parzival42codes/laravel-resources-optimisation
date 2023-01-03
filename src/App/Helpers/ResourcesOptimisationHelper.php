<?php

if (! function_exists('assetResourcesOptimisation')) {
    function assetResourcesOptimisation(string $parameter): string
    {
        return app('url')->route('laravelresourcesoptimisation',
            [
                'fileName' => $parameter,
            ]);
    }
}
