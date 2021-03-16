<?php

namespace App;

use App\AccessControl\Scopes\CrmOwnerTrait;
use DB;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Student extends Eloquent
{
    use CrmOwnerTrait;

    protected $table = 'students';
    protected $fillable = ['name', 'email', 'address', 'mobile', 'birthday', 'birthyear', 'parent_id', 'gender', 'created_at', 'updated_at'];

    protected static function boot()
    {
        parent::boot();
    }

    public function __construct($crm_owner = '', $attributes = [])
    {
        parent::__construct($attributes);
    }

    /**
     * Hiển thị danh sách các bản ghi.
     *
     * @param  string $keyword
     * @param  numeric $record
     * @param  numeric $page
     * @return $search
     */
    public static function search($keyword, $record, $start = 0, $sort=[])
    {

        $totalRecord = Student::select(DB::raw('count(*) as total'))
            ->leftJoin('parents', 'parents.id', '=', 'students.parent_id')
            ->leftJoin('classes', 'classes.id', '=', 'students.current_class')
            ->get()->pluck('total')->toArray();

        $totalRecordFilterd = $totalRecord;

        $search = Student::select('students.*', 'parents.fullname as parent_name', 'classes.name as class_name')
            ->leftJoin('parents', 'parents.id', '=', 'students.parent_id')
            ->leftJoin('classes', 'classes.id', '=', 'students.current_class');

        if ($keyword) {

            $totalRecordFilterd = Student::select(DB::raw('count(*) as total'))
                ->leftJoin('parents', 'parents.id', '=', 'students.parent_id')
                ->leftJoin('classes', 'classes.id', '=', 'students.current_class')
                ->where('students.name', 'like', '%' . $keyword . '%')
                ->orwhere('students.id', $keyword)
                ->orwhere('students.birthyear', $keyword)
                ->orwhere(function ($query) use ($keyword) {
                    $query->whereRaw("DATE_FORMAT(students.birthday, '%d-%m-%Y')=?", $keyword);
                })
                ->orwhere('students.stage', $keyword)
                ->orwhere('students.email', 'like', '%' . $keyword . '%')
                ->orwhere('students.student_code', 'like', '%' . $keyword . '%')
                ->orwhere('students.address', 'like', '%' . $keyword . '%')
                ->orwhere('students.mobile', 'like', '%' . $keyword . '%')
                ->orwhere('parents.fullname', 'like', '%' . $keyword . '%')
                ->orwhere('classes.name', 'like', '%' . $keyword . '%')
                ->get()->pluck('total')->toArray();

            $search->where('students.name', 'like', '%' . $keyword . '%')
                ->orwhere('students.id', $keyword)
                ->orwhere('students.birthyear', $keyword)
                ->orwhere(function ($query) use ($keyword) {
                    $query->whereRaw("DATE_FORMAT(students.birthday, '%d-%m-%Y')=?", $keyword);
                })
                ->orwhere('students.stage', $keyword)
                ->orwhere('students.email', 'like', '%' . $keyword . '%')
                ->orwhere('students.student_code', 'like', '%' . $keyword . '%')
                ->orwhere('students.address', 'like', '%' . $keyword . '%')
                ->orwhere('students.mobile', 'like', '%' . $keyword . '%')
                ->orwhere('parents.fullname', 'like', '%' . $keyword . '%')
                ->orwhere('classes.name', 'like', '%' . $keyword . '%');
        }

        if (count($sort) > 0) {
            $search->orderBy($sort['name'], $sort['dir'] );
        }

        $data = $search->offset($start)
            ->limit($record)
            ->get();

        $result = [
            "recordsTotal" => $totalRecord[0],
            "recordsFiltered" => $totalRecordFilterd[0],
            "data" => $data
        ];

        return $result;
    }

    /**
     * Xóa một bản ghi dựa vào id.
     *
     * @param  int  $id
     * @return void
     */
    public static function deleteStudent($id)
    {
        return DB::table('students')->where('id', $id)->delete();
    }

    /**
     * Thêm mới một bản ghi.
     *
     * @param  array $data
     * @return void
     */
    public static function store1($data)
    {
        $student = DB::table('students')->insert([
            ['name' => $data['name'],
                'email' => $data['email'],
                'student_code' => $data['student_code'],
                'address' => $data['address'],
                'mobile' => $data['mobile'],
                'birthday' => $data['birthday'],
                'gender' => $data['gender'],
                'created_at' => $data['created_at'],
                'updated_at' => $data['updated_at'],
            ],
        ]);
        return $student;
    }

    public static function getStudentByCrmID($crm_id)
    {
        // $result = Student::where('crm_id', $crm_id)->get();
        // if (count($result)) {
        //     return $result[0];
        // } else {
        //     return null;
        // }

        return DB::table('students')->select('*')->where('crm_id', $crm_id)->first();

    }

    /**
     * Thêm mới một bản ghi.
     *
     * @param  array $data
     * @return void
     */
    public static function insert($data)
    {
        $id = DB::table('students')->insertGetId($data);
        return $id;
    }

    /**
     * Hiển thị danh sách các bản ghi.
     *
     * @param  int $id
     * @return $student
     */
    public static function getStudent($id)
    {
        $student = Student::find($id);
        return $student;
    }

    /**
     * Update thông tin học sinh.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @param  array  $data
     * @return void
     */
    public static function update1($data, $id)
    {
        $student = DB::table('students')->where('id', $id)
            ->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'student_code' => $data['student_code'],
                'address' => $data['address'],
                'mobile' => $data['mobile'],
                'birthday' => $data['birthday'],
                'gender' => $data['gender'],
                'created_at' => $data['created_at'],
                'updated_at' => $data['updated_at'],
            ]);
        return $student;
    }

}