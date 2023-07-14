@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="col-lg-12 mb-5">
            @if(session()->has('type'))
                <div class="alert alert-{{session('type')}}">
                    {{session('message')}}
                </div>
            @endif
            <h3>All Users</h3>
            <div class="card mb-12">
                <div class="card-body">
                    <div class="card-title h4 text-center">
                    </div>
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <th>{{$user->id}}</th>
                                <td>{{$user->name}}</td>
                                <td>{{$user->email}}</td>
                                <td>
                                    <button onclick="document.getElementById('delete-{{$user->id}}').submit()" class="btn btn-sm btn-danger">
                                        Delete
                                    </button>
                                    <a href="{{route('users.edit', $user->id)}}" class="btn btn-sm btn-success">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                            <form id="delete-{{$user->id}}" action="{{route('users.destroy', $user->id)}}" method="post"> @csrf @method('delete') </form>

                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
