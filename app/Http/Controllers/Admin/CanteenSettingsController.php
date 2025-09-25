<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Canteen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CanteenSettingsController extends Controller
{
    public function index()
    {
        $canteen = Auth::user()->canteen;
        if (!$canteen) {
            return redirect()->back()->with('error', 'Kantin tidak ditemukan');
        }

        $days = [
            'monday' => 'Senin',
            'tuesday' => 'Selasa',
            'wednesday' => 'Rabu',
            'thursday' => 'Kamis',
            'friday' => 'Jumat',
            'saturday' => 'Sabtu',
            'sunday' => 'Minggu'
        ];

        $operatingHours = $canteen->operating_hours ?: [];
        $daysWithSchedule = [];

        foreach ($days as $dayKey => $dayName) {
            $daySchedule = $operatingHours[$dayKey] ?? [
                'is_open' => false,
                'original_is_open' => false, // Status asli
                'open_time' => '08:00',
                'close_time' => '15:00'
            ];

            // Jika tidak ada original_is_open, gunakan is_open sebagai default
            if (!isset($daySchedule['original_is_open'])) {
                $daySchedule['original_is_open'] = $daySchedule['is_open'];
            }

            $daysWithSchedule[] = [
                'key' => $dayKey,
                'name' => $dayName,
                'is_open' => $daySchedule['original_is_open'], // Tampilkan status asli di UI
                'original_is_open' => $daySchedule['original_is_open'],
                'open_time' => $daySchedule['open_time'],
                'close_time' => $daySchedule['close_time']
            ];
        }

        return view('admin.canteen.settings', [
            'canteen' => $canteen,
            'daysWithSchedule' => $daysWithSchedule
        ]);
    }

    public function updateSettings(Request $request)
    {
        $canteen = Auth::user()->canteen;
        if (!$canteen) {
            return redirect()->back()->with('error', 'Kantin tidak ditemukan');
        }

        $request->validate([
            'is_open' => 'sometimes|boolean',
            'operating_hours' => 'sometimes|array',
            'operating_hours.*.is_open' => 'sometimes|boolean',
            'operating_hours.*.original_is_open' => 'sometimes|boolean',
            'operating_hours.*.open_time' => 'required_if:operating_hours.*.original_is_open,1|date_format:H:i',
            'operating_hours.*.close_time' => 'required_if:operating_hours.*.original_is_open,1|date_format:H:i',
            'notes' => 'nullable|string|max:255'
        ]);

        $isMainOpen = $request->has('is_open') ? true : false;
        $operatingHours = [];

        if ($request->has('operating_hours')) {
            foreach ($request->operating_hours as $day => $schedule) {
                // Status asli (yang user atur manual)
                $originalIsOpen = isset($schedule['original_is_open']) && $schedule['original_is_open'] == '1';

                // Status aktif (dipengaruhi oleh main toggle)
                $isOpen = $isMainOpen && $originalIsOpen;

                $operatingHours[$day] = [
                    'is_open' => $isOpen, // Status aktif saat ini
                    'original_is_open' => $originalIsOpen, // Status asli yang disimpan
                    'open_time' => $schedule['open_time'] ?? '08:00',
                    'close_time' => $schedule['close_time'] ?? '15:00'
                ];
            }
        }

        $canteen->update([
            'is_open' => $isMainOpen,
            'operating_hours' => $operatingHours,
            'notes' => $request->notes
        ]);

        return redirect()->back()->with('success', 'Pengaturan jam operasional berhasil disimpan!');
    }

    public function quickToggle(Request $request)
    {
        $canteen = Auth::user()->canteen;
        if (!$canteen) {
            return response()->json(['error' => 'Kantin tidak ditemukan'], 404);
        }

        $newMainStatus = !$canteen->is_open;
        $operatingHours = $canteen->operating_hours ?: [];

        // Update status setiap hari berdasarkan main toggle dan original status
        foreach ($operatingHours as $day => $schedule) {
            $originalIsOpen = $schedule['original_is_open'] ?? false;
            $operatingHours[$day]['is_open'] = $newMainStatus && $originalIsOpen;
        }

        $canteen->update([
            'is_open' => $newMainStatus,
            'operating_hours' => $operatingHours
        ]);

        return response()->json([
            'success' => true,
            'is_open' => $canteen->is_open,
            'message' => $canteen->is_open ? 'Kantin dibuka' : 'Kantin ditutup'
        ]);
    }

    /**
     * Method untuk mendapatkan status operasional real-time
     * Berguna untuk API atau komponen lain yang butuh status terkini
     */
    public function getCurrentStatus()
    {
        $canteen = Auth::user()->canteen;
        if (!$canteen) {
            return response()->json(['error' => 'Kantin tidak ditemukan'], 404);
        }

        $today = strtolower(now()->format('l'));
        $currentTime = now()->format('H:i');
        $operatingHours = $canteen->operating_hours ?: [];

        $todaySchedule = $operatingHours[$today] ?? ['is_open' => false];

        $isOpenToday = $canteen->is_open &&
                      $todaySchedule['is_open'] &&
                      isset($todaySchedule['open_time']) &&
                      isset($todaySchedule['close_time']) &&
                      $currentTime >= $todaySchedule['open_time'] &&
                      $currentTime <= $todaySchedule['close_time'];

        return response()->json([
            'canteen_override_open' => $canteen->is_open,
            'is_open_today' => $isOpenToday,
            'today_schedule' => $todaySchedule,
            'current_time' => $currentTime
        ]);
    }
}
