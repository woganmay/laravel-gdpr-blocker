<?php

namespace WoganMay\GDPRBlocker;

use Closure;

class GDPRMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // Create IP Set
        $rangesFile = base_path("vendor/woganmay/gdpr-blackhole/eu-ranges.txt");
        $ipRanges = explode("\n", trim(file_get_contents($rangesFile)));
        $IPSet = new \IPSet\IPSet($ipRanges);

        if ($IPSet->match($_SERVER['REMOTE_ADDR']))
        {
            return response($this->getResponseHtml(), 451);
        }
        else
        {
            return $next($request);
        }

    }

    /**
     * @return string A pretty HTML error page
     */
    private function getResponseHtml()
    {
        $ip = $_SERVER['REMOTE_ADDR'];

        return '<!doctype html>
            <html>
                <head>
                    <meta charset="utf-8">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <meta name="viewport" content="width=device-width, initial-scale=1">
                    <title>Your IP address is blocked</title>
                    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
                    <style type="text/css">
                        html, body { background-color: #fff; color: #636b6f; font-family: \'Raleway\', sans-serif; font-weight: 100; height: 100vh; margin: 0; }
                        .flex-center { align-items: center; display: flex; justify-content: center; position: relative; height: 100vh; }
                        .content { text-align: center; }
                        .title { font-size: 48px; margin-bottom: 24px; }
                    </style>
                </head>
                <body>
                    <div class="flex-center">
                        <div class="content">
                            <div class="title">
                                Sorry!
                            </div>
                            <div>
                                Your IP address ('.$ip.') is originating from the EU, and has been blocked.
                            </div>
                        </div>
                    </div>
                </body>
            </html>';

    }

}