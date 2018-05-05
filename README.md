# laravel-gdpr-blocker

A simple middleware class that blocks visitors from any of the 28 EU member states.

### Installation

Install via Composer:

    require woganmay/laravel-gdrp-blocker
    
Once it's installed, it creates a new Middleware you can include in `app/Http/Kernel.php`:

    'web' => [
        ...
        \WoganMay\GDPRBlocker\GDPRMiddleware::class,
    ]
    
The middleware checks every request against a big list of EU IP addresses, and returns an HTML error page if the IP address is blocked.

### License

MIT
