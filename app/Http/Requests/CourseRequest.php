<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return 
            [
                'name'      =>'required',
                'code'      =>'required|unique:courses',
                'duration'  =>'required|numeric',
                'fee'       =>'required|numeric',
                'curriculum'=>'required',
                'level'     =>'required'
            
           
        ];
    }

    public function messages()
    {
        return  [
                'name.required'         => "Course name can't empty!",
                'code.required'         => "Code name can't empty!",
                'code.unique'           => "The code name already exits!",
                'duration.required'     => "Duration can't empty!",
                'fee.required'          => "Fee can't empty!",
                'curriculum.required'   => "Curriculum can't empty!",
                'level.required'        => "Level can't empty!",
                'fee.numeric'           => "Fee must be numeric",
                'duration.numeric'      => "Duration must be numeric"
            ];
    }
}
