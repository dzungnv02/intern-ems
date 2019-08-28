<?php

namespace App\Http\Controllers;

use App\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        return view('branch/branch_list');
    }

    public function detail(Request $request)
    {

    }

    public function add(Request $request)
    {
        return view('branch/branch_form');
    }

    public function edit(Request $request)
    {
        return view('branch/branch_form');
    }

    function list(Request $request) 
    {
        $inputs = $request->input();
        $filter = null;

        if (!empty($inputs)) {
            $filter = [];
            foreach($inputs as $field => $where) {
                if ($field == '_') continue;
                $value = $where['value'] == "null" ? null : $where['value'];
                $filter[] = [$field, $where['operator'], $value];
            }
        }

        $branch_list = Branch::getBranchs($filter);
        return response()->json(['code' => 1, 'data' => $branch_list], 200);
    }

    public function getBranch(Request $request)
    {
        $input = $request->all();
        $branch_id = $input['branch_id'];
        return response()->json(['code' => 1, 'data' => Branch::getBranch($branch_id)], 200);
    }

    public function insertBranch(Request $request)
    {
        $input = $request->all();
        $ary_data = [
            'branch_name' => $input['branch_name'],
            'address' => $input['address'],
            'phone_1' => $input['phone_1'],
            'phone_2' => $input['phone_2'],
            'email' => $input['email'],
            'leader' => $input['leader'],
        ];
        return response()->json(['code' => 1, 'data' => Branch::insertBranch($ary_data)], 200);
    }

    public function updateBranch(Request $request)
    {
        $input = $request->all();
        $ary_data = [
            'branch_name' => $input['branch_name'],
            'address' => $input['address'],
            'phone_1' => $input['phone_1'],
            'phone_2' => $input['phone_2'],
            'email' => $input['email'],
            'leader' => $input['leader'],
        ];
        $branch_id = $input['id'];
        return response()->json(['code' => 1, 'data' => Branch::updateBranch($branch_id, $ary_data)], 200);
    }

    public function deleteBranch(Request $request)
    {
        $input = $request->all();
        $branch_id = $input['id'];
        return response()->json(['code' => 1, 'data' => Branch::deleteBranch($branch_id)], 200);

    }
}
