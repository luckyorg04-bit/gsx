<?php
/**
 * GSX intentionally disables the legacy browser-based installer.
 *
 * The previous installer executed Composer, Artisan, database migrations and
 * seeders through unauthenticated HTTP requests. It also used migrate:fresh,
 * which can drop existing tables and data. Do not re-enable this endpoint.
 */
http_response_code(410);
header('Content-Type: text/html; charset=UTF-8');
header('Cache-Control: no-store, max-age=0');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GSX setup uses the command line</title>
    <style>
        body{font-family:system-ui,-apple-system,Segoe UI,sans-serif;background:#f4f6f8;color:#172033;margin:0;padding:2rem}
        main{max-width:680px;margin:8vh auto;background:#fff;padding:2rem;border-radius:16px;box-shadow:0 12px 36px #17203312}
        h1{margin-top:0} code{background:#eef2f7;padding:.15rem .35rem;border-radius:4px}
    </style>
</head>
<body>
<main>
    <h1>GSX installation endpoint disabled</h1>
    <p>The legacy web installer is disabled because it could execute server commands over HTTP and run destructive database resets.</p>
    <p>Install GSX using the reviewed command-line deployment procedure in <code>docs/GSX-INSTALLATION.md</code>. Do not expose an installer endpoint on a production server.</p>
    <p>This endpoint intentionally returns HTTP 410.</p>
</main>
</body>
</html>
