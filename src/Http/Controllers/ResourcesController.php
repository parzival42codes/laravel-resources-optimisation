<?php

namespace parzival42codes\laravelResourcesOptimisation\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;

class ResourcesController extends Controller
{
    protected Response $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function show(string $fileName): Response
    {
        /** @var array $mapping */
        $mapping = config('laravelResourcesOptimisation.map');

        if (isset($mapping[$fileName])) {
            $filePath = base_path() . $mapping[$fileName];

            if (is_file($filePath)) {
                $this->response->setContent(file_get_contents($filePath));

                return $this->response;
            }
        }

        app('debugbar')->disable();

        $fileName = public_path() . '/' . $fileName;

        if (! File::exists($fileName)) {
            abort(
                400,
                'File ' . $fileName . ' not exists'
            );
        }

        $this->response->setContent(File::get($fileName));

        return $this->response;
    }
}
