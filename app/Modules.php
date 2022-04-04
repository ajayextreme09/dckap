<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Modules extends Model {
    protected $table = 'modules';

    public function childrens()
    {
        return $this->hasMany($this, 'parent_id');
    }

    public function children()
    {
        return $this->childrens()->with('children')->withCount('childrens')->withCount('test_cases');
    }

    public function test_cases()
    {
        return $this->hasMany('App\TestCases', 'module_id', 'id');
    }
}
