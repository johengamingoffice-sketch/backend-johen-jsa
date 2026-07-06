<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$employee = App\Models\Employee::where('nama', 'like', '%Muhammad Ilyas%')->orWhere('nama', 'like', '%Al-Fadhlih%')->first();
if ($employee) {
    echo json_encode($employee->toArray(), JSON_PRETTY_PRINT);
} else {
    echo "not found\n";
}

// Try user table too
$user = App\Models\User::where('name', 'like', '%Muhammad Ilyas%')->orWhere('name', 'like', '%Al-Fadhlih%')->first();
if ($user) {
    echo "User found:\n";
    echo json_encode($user->toArray(), JSON_PRETTY_PRINT);
} else {
    echo "User not found\n";
}
