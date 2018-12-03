<?php

namespace App\Http\Controllers;

use App\StudentParent;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    public function getParentList(Request $request)
    {
        $inputs = $request->all();
        $student_id = isset($inputs['student_id']) ? $inputs['student_id'] : null;

        if ($student_id) {
            $list = StudentParent::getParentsOfStudent($student_id);
        } else {
            $list = StudentParent::all();
        }

        return response()->json(['code' => 1, 'data' => $list], 200);
    }

    public function mapingParentStudent(Request $request)
    {
        try {
            $inputs = $request->all();
            $student_id = isset($inputs['student_id']) ? $inputs['student_id'] : null;
            $parents = isset($inputs['parents']) ? $inputs['parents'] : [];
            if ($student_id && count($parents) > 0) {
                StudentParent::deleteByStudent($student_id);
                $now = date('Y-m-d H:i:s');
                foreach ($parents as $parent_id) {
                    $data = ['student_id' => $student_id, 'parent_id' => $parent_id];
                    StudentParent::insert($data);
                }
                return response()->json(['code' => 1, 'message' => 'inserted '. count($parents). ' records'], 200);
            }
            return response()->json(['code' => 1, 'message' => 'no data'], 204);

        } catch (\Exception $e) {
            return response()->json(['code' => 0, 'message' => $e->getMessage()], 500);
        }
    }
}
