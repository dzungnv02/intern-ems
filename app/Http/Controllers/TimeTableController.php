<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TimeTable;
use App\Classes;
use App\Holiday;

class TimeTableController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    	$id = $request->id;
        $timetable = TimeTable::getListTimeTable($id);
        return response()->json(['code' => 1,'message' => 'ket qua','data' => $timetable],200);
    }

    /**
     * Show the form for editing the specified resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $id = $request->id;
        if ($id){
            if (TimeTable::find($id) == null){
                return response()->json(['code' => 0,'message' => 'khong ton tai ngay nay'],200);
            }else{
                $timetable = TimeTable::edit($id);
                return response()->json(['code' => 1,'data' => $timetable],200);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update2(Request $request)
    {
        $id = $request->id;
        $class_id = request('class_id');
        $date = request('date');
        $week_days = date('w',strtotime($request->date));
        $time_start = request('time');
        $data = array(
            'date' => $request->date,
            'time' => $request->time,
            'week_days' => $week_days,
            'created_at' => date("Y-m-d"),
            'updated_at' => date("Y-m-d"),
        );
        $request->validate([
            'date' => 'required',
            'time' => 'required',
        ]);

        $holiday = [];
        $items = Holiday::getListHoliday();
        foreach ($items as $holi) {
            $holiday[] = $holi->holiday;
        };

        $timetable = TimeTable::join('classes','classes.id','=','timetables.class_id')
                    ->select('timetables.time', 'timetables.date','classes.duration')
                    ->where('timetables.class_id',$class_id)->get();
        $day_start = TimeTable::where('class_id',$class_id)->min('date');
        $ngay_hien_tai = date("Y/m/d");
        $time_end = date(' H:i:s',strtotime('+'.$timetable[0]['duration'].'hour',strtotime($time_start)));
        $z =0;
        for ($x=0; $x < count($holiday); $x++) {
            if (strtotime($date) == strtotime($holiday[$x]) || strtotime($date) < strtotime($day_start) || strtotime($date) < strtotime($ngay_hien_tai)) {
                $z++;
            }else{
                for ($i=0; $i < count($timetable); $i++) {
                    if ($date == $timetable[$i]['date']) {
                        $time_end_tkb = date(' H:i:s',strtotime('+'.$timetable[$i]['duration'].'hour',strtotime($timetable[$i]['time'])));
                        if (strtotime($time_end) < strtotime($timetable[$i]['time']) || strtotime($time_start) > strtotime($time_end_tkb)) {
                            // echo "ok them";
                        }else{
                            $z++;
                        }
                    }
                }
            }
        }

        if ($z != 0) {
            return response()->json(['code' => 0, 'message' => 'Trùng lịch học, ngày nghỉ lễ hoặc ngày không hợp lệ'],200);
        }else{
            $dataTime = TimeTable::update1($data,$id);
            return response()->json(['code' => 1,'message' => 'Cap nhat thanh cong'],200);
        }
    }
    
}
