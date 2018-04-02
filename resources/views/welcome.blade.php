@extends('layouts.app')
@section('css')
    <style>
        .left-sidebar{
            position: fixed;
            height: 90%;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-2 col-md-2 bg-white left-sidebar scroll-box">
            <nav class="hidden-xs-down bg-faded sidebar">
                <ul class="nav nav-pills flex-column">
                    @foreach($postcodes as $name => $group)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">{{ $name }}</a>
                            <ul class="dropdown-menu">
                                @foreach($group as $postcode)
                                    <li><a class="postcode" href="javascrip:void(0);"
                                           id="{{ $postcode->id }}">{{ $postcode->postcode }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </div>
        <div class="col-md-7 mx-auto">
            <div class="row">
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">5 closest bus stops</th>
                            <th scope="col">schools in 10 miles radius</th>
                            <th scope="col">addresses in that postcode</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td id="busstops">
                                <ul></ul>
                            </td>
                            <td id="schools">
                                <ul></ul>
                            </td>
                            <td id="addresses">
                                <ul></ul>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-12 mt-1">
                <a class="btn btn-primary" href="{{ route('download.users.csv') }}" role="button">DOWNLOAD CSV</a>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        var busstopsContainer = $("td#busstops > ul");
        var schoolsContainer = $("td#schools > ul");
        var addressesContainer = $("td#addresses > ul");
        $("a.postcode").on('click', function (e) {
            e.preventDefault();
            let id = $(this).attr('id');
            loadPostcode(id);
        });

        function populateData(element, data) {
            element.html('');
            data.forEach(function (item) {
                element.append(`<li>${item.name}</li>`);
            });
        }

        function loadPostcode(id) {
            axios.get(`/postcodes/${id}`)
                .then(function (response) {
                    populateData(busstopsContainer, response.data.busstops);
                    populateData(schoolsContainer, response.data.schools);

                    let addresses = [];
                    Object.keys(response.data.addresses).forEach(function (key) {
                        addresses.push({'name': response.data.addresses[key].name});
                    });

                    populateData(addressesContainer, addresses);
                })
                .catch(function (error) {
                    console.log(error);
                });
        }
    </script>
@endsection