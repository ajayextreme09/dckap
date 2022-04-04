<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Hash;
use App\User;
use App\Modules;
use App\TestCases;

class TestCasesController extends Controller
{
	public function testCaseSave(Request $request)
	{
		if($request->id==0) {
			$testCase = new TestCases;
			$message = "save";
		}
		else {
			$testCase = TestCases::where('id', $request->id)->first();
			$message = "update";
		}
		$testCase->name = $request->name;
		$testCase->module_id = $request->module_id;
		if($testCase->save())
			return response()->json(['success'=>true, 'message'=>'Test case '.$message.'d successful', 'testCase'=>$testCase]);
		else
			return response()->json(['success'=>false, 'message'=>'Test case '.$message.' failed']);
	}

	public function testCaseDelete($id)
	{
		$testCase = TestCases::where('id', $id)->first();
		if($testCase->delete())
			return response()->json(['success'=>true, 'message'=>'Test case deleted successful', 'testCase'=>$testCase]);
		else
			return response()->json(['success'=>false, 'message'=>'Test case delete failed']);
	}

	public function getTestCase($id)
	{
		$testCase = TestCases::where('id', $id)->first();
		if($testCase)
			return response()->json(['success'=>true, 'message'=>'Test case get details successful', 'testCase'=>$testCase]);
		else
			return response()->json(['success'=>false, 'message'=>'Test case get details failed']);
	}

	public function testCases($module_id)
	{
		$testCases = TestCases::where('module_id', $module_id)->get();
		if($testCases)
			return response()->json(['success'=>true, 'message'=>'Test cases get successful', 'testCases'=>$testCases]);
		else
			return response()->json(['success'=>false, 'message'=>'Test cases get failed']);
	}
}
