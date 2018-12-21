<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
        <title>Under Maintenance!</title>
    </head>
    <style>
    body {
        background: linear-gradient(rgba(0, 0, 0, 0.55), rgba(0, 0, 0, 0.55)), 
        url({{ str_replace('-large', '', App\Setting::get_setting('maintenance_bg')) }});
        background-size: cover;
        color: #fff;
        background-color: #080808;
        text-shadow: 6px 6px #121212;
    }
    .cover { margin: 15% auto; }
    footer { background: #00000091; }
    </style>
    <body class="text-center">
        <main role="main" class="cover">
            <div>
                <h1 class="mb-5">Our website is currently undergoing<br> scheduled maintenance.</h1>
                <h2 class="text-uppercase text-warning mb-5">Please check back soon!</h2>
                <h2>Thank you for your understanding.</h2>                
            </div>
        </main>
        <footer class="mt-auto">
            <div class="inner">
            <div class="text-white p-2">{{ App\Setting::get_setting('site_title') }} © {{ date('Y') }}</a>
            </div>
        </footer>
    </body>
</html>