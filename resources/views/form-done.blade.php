@extends('layouts.app')

@section('title', 'DONE')

@section('navbar')
    @parent
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <h1>Done</h1>
                <img src="{{ $image->embedded }}">
            </div>
        </div>
    </div>
@endsection
@section('scripts')

@endsection
