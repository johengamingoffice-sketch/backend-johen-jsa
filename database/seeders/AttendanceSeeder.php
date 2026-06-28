<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::all();
        if ($employees->isEmpty()) return;

        $statuses = ['hadir', 'hadir', 'hadir', 'hadir', 'hadir', 'hadir', 'hadir', 'izin', 'sakit', 'alpha'];
        $weekendStatuses = ['hadir', 'hadir', 'izin', 'alpha'];

        foreach ($employees as $employee) {
            for ($i = 0; $i < 30; $i++) {
                $date = now()->subDays($i);
                $pool = $date->isSunday() || $date->isSaturday() ? $weekendStatuses : $statuses;
                $status = $pool[array_rand($pool)];

                $timeIn = null;
                $timeOut = null;

                if (in_array($status, ['hadir'])) {
                    $hour = rand(7, 9);
                    $minute = rand(0, 59);
                    $timeIn = sprintf('%02d:%02d:00', $hour, $minute);

                    $outHour = rand(16, 18);
                    $outMinute = rand(0, 59);
                    $timeOut = sprintf('%02d:%02d:00', $outHour, $outMinute);
                }

                Attendance::create([
                    'employee_id' => $employee->id,
                    'date' => $date->format('Y-m-d'),
                    'time_in' => $timeIn,
                    'time_out' => $timeOut,
                    'status' => $status,
                ]);
            }
        }

        if ($this->command) {
            $this->command->info('Attendance seeded for ' . $employees->count() . ' employees over 30 days.');
        }
    }
}
