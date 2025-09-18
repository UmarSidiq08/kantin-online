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
        $days = ['monday'=>'Senin','tuesday'=>'Selasa','wednesday'=>'Rabu','thursday'=>'Kamis','friday'=>'Jumat','saturday'=>'Sabtu','sunday'=>'Minggu'];
        $operatingHours = $canteen->operating_hours ?: [];
        $daysWithSchedule = [];
        foreach ($days as $dayKey => $dayName) {
            $daySchedule = $operatingHours[$dayKey] ?? ['is_open'=>false,'open_time'=>'08:00','close_time'=>'15:00'];
            $daysWithSchedule[] = ['key'=>$dayKey,'name'=>$dayName,'is_open'=>$daySchedule['is_open'],'open_time'=>$daySchedule['open_time'],'close_time'=>$daySchedule['close_time']];
        }
        return view('admin.canteen.settings', ['canteen'=>$canteen,'daysWithSchedule'=>$daysWithSchedule]);
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
            'operating_hours.*.open_time' => 'required_if:operating_hours.*.is_open,1|date_format:H:i',
            'operating_hours.*.close_time' => 'required_if:operating_hours.*.is_open,1|date_format:H:i',
            'notes' => 'nullable|string|max:255'
        ]);
        $operatingHours = [];
        if ($request->has('operating_hours')) {
            foreach ($request->operating_hours as $day => $schedule) {
                $operatingHours[$day] = [
                    'is_open' => isset($schedule['is_open']) && $schedule['is_open'] == '1',
                    'open_time' => $schedule['open_time'] ?? '08:00',
                    'close_time' => $schedule['close_time'] ?? '15:00'
                ];
            }
        }
        $canteen->update([
            'is_open' => $request->has('is_open') ? true : false,
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
        $canteen->update(['is_open' => !$canteen->is_open]);
        return response()->json([
            'success' => true,
            'is_open' => $canteen->is_open,
            'message' => $canteen->is_open ? 'Kantin dibuka' : 'Kantin ditutup'
        ]);
    }
}
