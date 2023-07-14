@extends('layouts.master')
@section('content')
    <div class="small badge text-bg-primary mb-2">#User {{$users}}</div>
    <div class="small badge text-bg-danger mb-2">#Posts {{$posts}}</div>
    <div class="small badge text-bg-warning mb-2">#Comments {{$comments}}</div>
    <div class="small badge text-bg-secondary mb-2">#Reacts {{$reacts}}</div>


    <div class="row">
        <div class="col-lg-3 mb-5">
            <div class="card mb-12">
                <div class="card-body">
                    <div class="card-title h4 text-center">
                        <div class="small">
                            <a  type="button" class="btn btn-outline-primary"
                                style="display: block;--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                                Manage Users →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 mb-5">
            <div class="card mb-12">
                <div class="card-body">
                    <div class="card-title h4 text-center">
                        <div class="small">
                            <a  type="button" class="btn btn-outline-danger"
                                style="display: block;--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                                Manage Posts →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
