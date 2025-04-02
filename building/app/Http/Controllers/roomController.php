<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Laravel\Facades\Image;

class roomController extends Controller
{

    public  function registerRoom(){
        $rooms = DB::table('rooms')->get();
        $buildings = DB::table('buildings')->select('*')->get();
        return view('admin.register_room', compact('buildings','rooms'));
    }

    public  function selectBuiding(){

        $buildings = DB::table('buildings')->select('*')->get();
        $rooms = DB::table('rooms', 'r')
            ->join('buildings as b', 'r.building_id', '=', 'b.id')
            ->select('r.*', 'b.id as id_building')
            ->where('r.building_id', $_POST['building_id'])
            ->where('r.status','available')
            ->get();
            return response()->json([
                'success' => true,
                'rooms' => $rooms
            ]);

    }

    public function getDateRoom(Request $request){
        $rooms = $request->input('rooms_id');

        $dates = DB::table('schedules as s')
        ->join('rooms as r', 'r.id', '=', 's.room_id')
        ->join('shift_times as st', 'st.id', '=', 's.shift_id')
        ->select('s.specific_date')
        ->where('r.id', $rooms)
        ->groupBy('s.specific_date')
        ->havingRaw('COUNT(s.schedule_id) = 6')
        ->get();


        return response()->json([
            'success' => true,
            'dates' => $dates
        ]);
    }


    public function getTimeNone(){
        $date = $_POST['date']; // Ví dụ: 2025-04-02
        $rooms = $_POST['room_id'];

        $blockedHours = DB::table('shift_times')
        ->selectRaw('DISTINCT HOUR(start_time) AS hour_start, HOUR(start_time) + 1 AS hour_between, HOUR(end_time) AS hour_end')
        ->join('schedules', 'shift_times.id', '=', 'schedules.shift_id')
        ->where('schedules.specific_date', $date)
        ->where('schedules.room_id', $rooms)
        ->orderBy('hour_start', 'ASC')->get();

        return response()->json([
            'success' => true,
            'blockedHours' => $blockedHours
        ]);
    }
}
