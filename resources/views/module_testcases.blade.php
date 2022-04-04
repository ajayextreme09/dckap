@extends('layouts.app')

@section('custom_css')
<link href="{{asset('css/test_cases.css')}}" rel="stylesheet" type="text/css" />
@stop

@section('content')
<div id="app">
	<module-testcases-component></module-testcases-component>
</div>
@stop

@section('custom_js')
<script defer type="text/javascript" src="{{asset('/js/app.js')}}"></script>
@stop