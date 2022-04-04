<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Hash;
use App\User;
use App\Modules;
use App\TestCases;
use DB;

class HomeController extends Controller
{
	private $modules_ids = array();
	private $testcases_ids = array();

	public function index()
	{
		return view('modules');
	}

	public function moduleTestCases()
	{
		return view('module_testcases');
	}

	public function modulesList()
	{
		$modules = Modules::withCount('childrens')->withCount('test_cases')->with('children')->where('parent_id',0)->get()->toArray();
		if(sizeof($modules)>0)
			return response()->json(['success'=>true, 'message'=>'Get modules successful', 'modules'=>$modules]);
		else
			return response()->json(['success'=>false, 'message'=>'Get modules failed']);
	}

	public function moduleSave(Request $request)
	{
		$module = new Modules;
		$module->text = $request->module_name;
		$module->parent_id = $request->parent_id;
		$module->opened = false;
		if($module->save())
			return response()->json(['success'=>true, 'message'=>'Module saved successful', 'module'=>$module]);
		else
			return response()->json(['success'=>false, 'message'=>'Module save failed']);
	}

	public function moduleUpdate(Request $request)
	{
		$module = Modules::where('id', $request->id)->update(['parent_id'=>$request->parent_id]);
		if($module)
			return response()->json(['success'=>true, 'message'=>'Module updated successful', 'module'=>$module]);
		else
			return response()->json(['success'=>false, 'message'=>'Module update failed']);
	}

	public function modulesDelete(Request $request)
	{
		$modules = Modules::with('children')->whereIn('parent_id',$request->ids)->get()->toArray();
		$this->getIds($modules);
		
		$testCase = TestCases::where('module_id', $request->ids)->pluck('id');
		foreach ($testCase as $key => $value) {
			array_push($this->testcases_ids, $value);
		}
		
		foreach ($request->ids as $key => $value) {
			array_push($this->modules_ids, $value);
		}

		$modules_ids = $this->modules_ids;
		$testcases_ids = $this->testcases_ids;

		// logger()->info('modules_ids=');
		// logger()->info($modules_ids);
		// logger()->info('testcases_ids=');
		// logger()->info($testcases_ids);
		
		$success = false;
		DB::beginTransaction();
		try {
		    $testCaseDelete = TestCases::whereIn('id', $testcases_ids)->delete();
			$testCaseDelete = Modules::whereIn('id', $modules_ids)->delete();
		    DB::commit();
			$success = true;
		} catch (\Exception $e) {
		    DB::rollback();
			logger()->info('Delete modules error=');
			logger()->info($e->getMessage());
		}
		if($success)
			return response()->json(['success'=>true, 'message'=>'Modules deleted successful']);
		else
			return response()->json(['success'=>false, 'message'=>'Modules delete failed']);
	}

	public function moduleDetails($id)
	{
		$module = Modules::withCount('childrens')->where('parent_id', $id)->get();
		if($module)
			return response()->json(['success'=>true, 'message'=>'Module deleted successful', 'module'=>$module]);
		else
			return response()->json(['success'=>false, 'message'=>'Module delete failed']);
	}

	function getIds($tree) {
        foreach ($tree as $j) {
            array_push($this->modules_ids, $j['id']);
            $testCase = TestCases::where('module_id', $j['id'])->pluck('id');
			foreach ($testCase as $key => $value) {
				array_push($this->testcases_ids, $value);
			}
            if(isset($j['children'])){
                $this->getIds($j['children']);
            }
        }
    }
}
