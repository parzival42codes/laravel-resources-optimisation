<?php

namespace parzival42codes\laravelResourcesOptimisation\Providers;

use GuzzleHttp\Psr7\MimeType;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Http\Response;
use Illuminate\Support\ServiceProvider;
use parzival42codes\laravelResourcesOptimisation\App\Services\Compress;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register any other events for your application.
     *
     * @param  Dispatcher  $events
     * @return void
     */
    public function boot(Dispatcher $events)
    {
        if (! app()->runningInConsole()) {
            $events->listen('Illuminate\Foundation\Http\Events\RequestHandled',
                function (RequestHandled $requestHandled) {
                    $requestHandled->response->header('Cache-Control', 'public');

                    $routeCurrent = \Route::current();

                    $contentMimeType = null;

                    if ($routeCurrent !== null) {
                        $routeCurrentUri = $routeCurrent->uri();

                        $fileName = $routeCurrent->parameter('fileName');

                        if (is_string($fileName)) {
                            $contentMimeType = MimeType::fromFilename($fileName);
                        }

                        if ($contentMimeType === null) {
                            if ($routeCurrentUri) {
                                if (str_contains($routeCurrentUri, 'javascript')) {
                                    $contentMimeType = 'application/javascript';
                                } elseif (str_contains($routeCurrentUri, 'stylesheets')) {
                                    $contentMimeType = 'text/css';
                                }
                            }
                        }
                    }

                    $content = $requestHandled->response->getContent();

                    if ($content) {
                        if (str_contains($content, '<html')) {
                            $contentMimeType = 'text/html';
                            $requestHandled->response->header('cache-control',
                                'public, no-store, no-cache, must-revalidate, max-age=0');
                        }
                    }

                    if ($requestHandled->response instanceof Response) {
                        new Compress($requestHandled->request, $requestHandled->response,
                            $contentMimeType ?? 'text/plain');
                    }
                });
        }
    }
}
