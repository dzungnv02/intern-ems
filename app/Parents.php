<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\AccessControl\Scopes\CrmOwnerTrait;

class Parents extends Model
{
    use CrmOwnerTrait;
    protected $table = 'parents';

    public static function insertOne($record)
    {
        return Parents::insertGetId($record);
    }

    public static function updateOne($id, $data)
    {
        return Parents::where('id', $id)->update($data);
    }

    public static function getParents()
    {
        return Parents::select('*')->get()->toArray();
    }

    public static function getParent($search_value, $field = 'id')
    {
        return Parents::select('*')
                ->where($field, $search_value)
                ->get()->toArray();
    }

    public static function getParentByCrmId($crm_id)
    {
        return Parents::where('crm_id', '=', $crm_id)->first();
    }

    public static function getParentByCrmContactId($crm_contact_id)
    {
        return Parents::where('crm_contact', '=', $crm_contact_id)->first();
    }
}
