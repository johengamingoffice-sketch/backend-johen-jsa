<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

App\Models\Attendance::truncate();
echo "Truncated attendances\n";

\$seeder = new Database\Seeders\AttendanceSeeder();
\$seeder->call(\$app->make(Illuminate\Container\Container::class), function() {});
\$seeder->run();

echo "Re-seeded\n";

\$count = App\Models\Attendance::count();
echo "Total attendances: \$count\n";
