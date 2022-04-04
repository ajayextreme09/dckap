@extends('layouts.app')

@section('custom_css')
<link href="{{asset('css/modules.css')}}" rel="stylesheet" type="text/css" />
@stop

@section('content')
<div id="app">
	<modules-component></modules-component>
</div>
@stop

@section('custom_js')
<script defer type="text/javascript" src="{{asset('/js/app.js')}}"></script>
@stop