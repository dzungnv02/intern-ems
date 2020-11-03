<?php
namespace App\AccessControl\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope as ScopeInterface;
use Illuminate\Database\Query\Builder as BaseBuilder;
use Illuminate\Support\Facades\DB;


class CrmOwnerScope implements ScopeInterface
{

    public function apply(Builder $builder, Model $model)
    {
        if ($model->getTable() == 'classes') {
            return $this->buildForClassesTable($builder, $model);
        }
        else if ($model->getTable() == 'rev_n_exp') {
            return $this->buildForInvoiceTable($builder, $model);
        }

        $owner_id = $model->getOwner();
        $role = $model->getRole();
        $column = $model->getQualifiedCrmOwnerColumn();
        if (($role != 1 && $role != 4) && $owner_id != null) {
            $builder->where($column, 'like', '%' . $owner_id . '%');
        }
    }

    protected function buildForClassesTable(Builder $builder, Model $model)
    {
        $role = $model->getRole();
        $owner_id = $model->getOwner();
        if (($role != 1 && $role != 4) && $owner_id != null) {
            $builder->join('branch', 'branch.id', 'classes.branch_id')
                    ->where('branch.crm_owner', '=', $owner_id);
        }
        else {
            $builder->leftJoin('branch','branch.id', '=', 'classes.branch_id' );
        }
    }

    protected function buildForInvoiceTable(Builder $builder, Model $model)
    {
        $role = $model->getRole();
        $owner_id = $model->getOwner();
        if (($role != 1 && $role != 4) && $owner_id != null) {

            $branch_owner = DB::table('staffs')
                            ->select('branch.crm_owner', 'staffs.id as staff_id')
                            ->join('branch','staffs.branch_id', '=','branch.id');
            
            $builder->joinSub($branch_owner, 'branch_owner', function($join){
                $join->on('rev_n_exp.created_by', '=', 'branch_owner.staff_id');
            })
            ->where('branch_owner.crm_owner', '=', $owner_id);
        }
    }

    /**
     * Remove scope from the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder  $builder
     * @param \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function remove(Builder $builder, Model $model)
    {

    }

    /**
     * Remove scope constraint from the query.
     *
     * @param  \Illuminate\Database\Query\Builder  $builder
     * @param  int  $key
     * @return void
     */
    protected function removeWhere(BaseBuilder $query, $key)
    {
        unset($query->wheres[$key]);

        $query->wheres = array_values($query->wheres);
    }

    /**
     * Remove scope constraint from the query.
     *
     * @param  \Illuminate\Database\Query\Builder  $builder
     * @param  int  $key
     * @return void
     */
    protected function removeBinding(BaseBuilder $query, $key)
    {
        $bindings = $query->getRawBindings()['where'];

        unset($bindings[$key]);

        $query->setBindings($bindings);
    }

    /**
     * Extend Builder with custom method.
     *
     * @param \Illuminate\Database\Eloquent\Builder  $builder
     */
    protected function addWithDrafts(Builder $builder)
    {
        $builder->macro('withDrafts', function (Builder $builder) {
            $this->remove($builder, $builder->getModel());

            return $builder;
        });
    }
}
