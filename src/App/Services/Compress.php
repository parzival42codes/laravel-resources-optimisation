<?php

namespace parzival42codes\laravelResourcesOptimisation\App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Compress
{
    private string $contentType;

    private string|bool $lastModify;

    private Request $request;

    private Response $response;

    public function __construct(
        Request $request,
        Response $response,
        string $contentType,
        string $lastModify = ''
    ) {
        $this->contentType = $contentType;
        $this->request = $request;
        $this->response = $response;
        $this->lastModify = $lastModify;
        $this->compress();
    }

    public function compress(): void
    {
        $headersIfModifiedSince = $this->request->headers->get('If-Modified-Since') ?? '';

        if ($this->lastModify !== '' && $headersIfModifiedSince !== null && (strtotime($headersIfModifiedSince) === $this->lastModify)) {
            $this->response->setStatusCode(304)
                ->header('Last-Modified',
                    [
                        $this->lastModify,
                    ]);
        } else {
            $this->response->header('Content-Type',
                $this->contentType);

            $content = $this->response->getContent();
            if ($content) {
                $contentGz = "\x1f\x8b\x08\x00\x00\x00\x00\x00";
                $contentCompress = gzcompress($content,
                    1);

                if ($contentCompress) {
                    $size = strlen($content);
                    $contentGz .= substr($contentCompress,
                        0,
                        -4);
                    $contentGz .= pack('V',
                        crc32($content));
                    $contentGz .= pack('V',
                        $size);
                    $this->response->setContent($contentGz);

                    $this->prepareHeader(strlen($contentGz),
                        md5($content));
                } else {
                    $this->response->setContent('');
                }
            } else {
                $this->response->setContent('');
            }
        }
    }

    protected function prepareHeader(
        int $contentLength,
        string $hash
    ): void {
        $this->response->header('Content-Encoding',
            'gzip')
            ->header('pragma',
                'public')
            ->header('Last-Modified',
                [$this->lastModify])
            ->header('Connection',
                'keep-alive')
            ->header('keep-alive',
                'timeout=5, max=100')
            ->header('Expires',
                gmdate('D, d M Y H:i:s',
                    time() + 31536000).' GMT')
            ->header('ETag',
                $hash)
            ->header('Content-Length',
                [
                    $contentLength,
                ])
            ->setStatusCode(200);
    }
}
