<?php
namespace App\AccessControl\Scopes;
use Illuminate\Http\Request;

trait CrmOwnerTrait
{
	/**
	 * Boot the scope.
	 *
	 * @return void
	 */
	public static function bootCrmOwnerTrait()
	{
		static::addGlobalScope(new CrmOwnerScope);
	}

	public function getOwner()
	{
		$all = app('Illuminate\Http\Request')->all();
		return $all['logged_user']->crm_owner;
	}

	public function getRole()
	{
		$all = app('Illuminate\Http\Request')->all();
		return isset($all['logged_user']) ? $all['logged_user']->role : 1;
	}

	public function getQualifiedCrmOwnerColumn()
	{
		return $this->getTable() . '.crm_owner';
	}

	/**
	 * Get the query builder without the scope applied.
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public static function withDrafts()
	{
		return with(new static )->newQueryWithoutScope(new CrmOwnerScope);
	}
}
