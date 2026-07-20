<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function robots(): Response
    {
        $url = rtrim((string) config('app.url'), '/');

        return response(
            implode("\n", [
                'User-agent: *',
                'Disallow:',
                sprintf('Sitemap: %s/sitemap.xml', $url),
                '',
            ]),
            Response::HTTP_OK,
            ['Content-Type' => 'text/plain; charset=UTF-8'],
        );
    }

    public function sitemap(): Response
    {
        $url = rtrim((string) config('app.url'), '/');
        $escapedUrl = htmlspecialchars($url, ENT_XML1 | ENT_COMPAT, 'UTF-8');
        $xml = sprintf(
            <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>%s</loc>
    </url>
</urlset>
XML,
            $escapedUrl,
        );

        return response($xml, Response::HTTP_OK, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
