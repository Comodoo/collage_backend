<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Login as admin
$admin = \App\Models\User::where('role', 'admin')->first();

$request = Illuminate\Http\Request::create('/api/registrations/2/approve', 'POST');
$request->setUserResolver(function () use ($admin) {
    return $admin;
});

$response = $kernel->handle($request);
echo "STATUS: " . $response->getStatusCode() . "\n";
echo "CONTENT: " . $response->getContent() . "\n";
