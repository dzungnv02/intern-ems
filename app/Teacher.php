<?php
namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $table = 'teachers';
    protected $fillable = ['name', 'birthdate', 'mobile', 'email', 'address', 'experience', 'certificate', 'description', 'nationality'];

    /**

     * Hiển thị danh sách các bản ghi.
     *
     * @param  string $keyword
     * @param  numeric $record
     * @param  numeric $page
     * @return void
     */
    public static function search($keyword, $record, $page = 1)
    {
        $start = ($page - 1) * $record;
        $search = Teacher::orderBy('id', 'desc')->where('name', 'like', '%' . $keyword . '%')
            ->orwhere('birthdate', 'like', '%' . $keyword . '%')
            ->orwhere('experience', 'like', '%' . $keyword . '%')
            ->orwhere('address', 'like', '%' . $keyword . '%')
            ->orwhere('mobile', 'like', '%' . $keyword . '%')
            ->orwhere('email', 'like', '%' . $keyword . '%')
            ->orwhere('nationality', 'like', '%' . $keyword . '%')
            ->orwhere('certificate', 'like', '%' . $keyword . '%')
            ->orwhere('description', 'like', '%' . $keyword . '%')
            ->offset($start)->limit($record)->get();
        return $search;
    }

    public static function getTeacher($value, $field = 'id')
    {
        $result = DB::table('teachers')->select('*')->where($field, $value)->get()->toArray();
        return $result;
    }

    // public static function update($id, $aryData) 
    // {
    //     return DB::table('teachers')->where('id', $id)->update($aryData);
    // }

    /**
     * Xóa một bản ghi dựa vào id.
     *
     * @param  int  $id
     * @return void
     */
    public static function deleteTeacher($teacher_id)
    {
        return DB::table('teachers')->where('id', $teacher_id)->delete();
    }

    /**
     * Thêm mới một bản ghi.
     *
     * @param  array $data
     * @return void
     */
    public static function insert($data)
    {
        $id = DB::table('teachers')->insertGetId($data);
        return $id;
    }

    /**
     * EDIT giáo viên.
     *
     * @param  int $id
     * @return void
     */
    public static function edit1($id)
    {
        $teacher = DB::table('teachers')->where('id', $id)->get();
        return $teacher;
    }

    /**
     * Update thông tin giáo viên.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return void
     */
    public static function update1($data, $id)
    {
        $teacher = DB::table('teachers')->where('id', $id)
            ->update(['name' => $data['name'],
                'experience' => $data['experience'],
                'description' => $data['description'],
                'nationality' => $data['nationality'],
                'address' => $data['address'],
                'mobile' => $data['mobile'],
                'email' => $data['email'],
                'birthdate' => $data['birthdate'],
                'certificate' => $data['certificate'],
                // 'created_at' => $data['created_at'],
                'updated_at' => $data['updated_at'],
            ]);
        return $teacher;
    }
}
