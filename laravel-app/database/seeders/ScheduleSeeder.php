<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Seed initial schedules similar to the legacy dataset.
     */
    public function run(): void
    {
        $seedSchedules = [
            ['tanggal' => '2024-08-01', 'waktu' => '09:00', 'slot' => 3, 'jenis_treatment' => 'HC1'],
            ['tanggal' => '2024-08-01', 'waktu' => '11:00', 'slot' => 3, 'jenis_treatment' => 'HC7'],
            ['tanggal' => '2024-08-01', 'waktu' => '13:00', 'slot' => 2, 'jenis_treatment' => 'HC23'],
            ['tanggal' => '2024-08-02', 'waktu' => '10:00', 'slot' => 3, 'jenis_treatment' => 'HC4'],
            ['tanggal' => '2024-08-02', 'waktu' => '14:00', 'slot' => 3, 'jenis_treatment' => 'HC10'],
            ['tanggal' => '2024-08-02', 'waktu' => '16:00', 'slot' => 2, 'jenis_treatment' => 'HC20'],
            ['tanggal' => '2024-08-03', 'waktu' => '09:00', 'slot' => 4, 'jenis_treatment' => 'HC13'],
            ['tanggal' => '2024-08-03', 'waktu' => '11:30', 'slot' => 2, 'jenis_treatment' => 'HC19'],
            ['tanggal' => '2024-08-03', 'waktu' => '15:00', 'slot' => 3, 'jenis_treatment' => 'HC30'],
        ];

        foreach ($seedSchedules as $schedule) {
            Schedule::query()->updateOrCreate(
                [
                    'jenis_treatment' => $schedule['jenis_treatment'],
                    'tanggal' => $schedule['tanggal'],
                    'waktu' => $schedule['waktu'],
                ],
                ['slot' => $schedule['slot']]
            );
        }
    }
}
