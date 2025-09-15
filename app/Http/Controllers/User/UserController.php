<?php

namespace App\Http\Controllers\User;

use App\Models\Canteen;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        $canteens = Canteen::all();

        // Process setiap kantin dengan status dan info jam operasional
        $canteensWithStatus = $canteens->map(function ($canteen) {
            $isOpen = $canteen->isOpen();
            $todaySchedule = $canteen->getTodaySchedule();
            $nextOpenTime = !$isOpen ? $canteen->getNextOpenTime() : null;

            return [
                'id' => $canteen->id,
                'name' => $canteen->name,
                'is_open' => $isOpen,
                'today_schedule' => $todaySchedule,
                'next_open_time' => $nextOpenTime,
                'status_text' => $isOpen ? 'Buka' : 'Tutup',
                'status_color' => $isOpen ? 'green' : 'red',
                'schedule_text' => $this->getScheduleText($todaySchedule, $nextOpenTime, $isOpen)
            ];
        });

        return view('user.dashboard', compact('canteensWithStatus'));
    }

    private function getScheduleText($todaySchedule, $nextOpenTime, $isOpen)
    {
        if ($isOpen && $todaySchedule) {
            return "Buka: {$todaySchedule['open_time']} - {$todaySchedule['close_time']}";
        }

        if (!$isOpen && $nextOpenTime) {
            return "Buka lagi: " . $nextOpenTime->format('d/m H:i');
        }

        if (!$isOpen && $todaySchedule) {
            return "Jam buka: {$todaySchedule['open_time']} - {$todaySchedule['close_time']}";
        }

        return null;
    }

    public function pilihKantin($id)
    {
        $canteen = Canteen::findOrFail($id);

        // Validasi apakah kantin sedang buka
        if (!$canteen->isOpen()) {
            $nextOpenTime = $canteen->getNextOpenTime();
            $message = 'Kantin ' . $canteen->name . ' sedang tutup.';

            if ($nextOpenTime) {
                $message .= ' Buka lagi pada ' . $nextOpenTime->format('d/m/Y H:i') . '.';
            }

            return redirect()->back()->with('error', $message);
        }

        session(['selected_canteen_id' => $canteen->id]);

        return redirect()->route('user.orders.index')->with('success', 'Kantin ' . $canteen->name . ' dipilih!');
    }
}
