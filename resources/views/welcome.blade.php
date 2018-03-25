@extends('layouts.app')
@section('css')
    <style>

    </style>
@endsection
@section('content')
    <div class="row">
    <div class="col-sm-2 col-md-2 bg-white left-sidebar scroll-box">
        <nav class="hidden-xs-down bg-faded sidebar">
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="#">Overview <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">Reports</a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Submenu 1-1</a></li>
                        <li><a href="#">Submenu 1-2</a></li>
                        <li><a href="#">Submenu 1-3</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#">Analytics</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">Export</a>
                </li>
                @foreach($postcodes as $name => $group)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">{{ $name }}</a>
                        <ul class="dropdown-menu">
                            @foreach($group as $postcode)
                                <li><a class="postcode" href="#" id="{{ $postcode->id }}">{{ $postcode->postcode }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>


        </nav>
    </div>
        <div class="col-md-5 mx-auto">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">First</th>
                    <th scope="col">Last</th>
                    <th scope="col">Handle</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td>Otto</td>
                    <td>@mdo</td>
                </tr>
                <tr>
                    <th scope="row">2</th>
                    <td>Jacob</td>
                    <td>Thornton</td>
                    <td>@fat</td>
                </tr>
                <tr>
                    <th scope="row">3</th>
                    <td>Larry</td>
                    <td>the Bird</td>
                    <td>@twitter</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection