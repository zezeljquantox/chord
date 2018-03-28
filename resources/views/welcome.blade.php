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
                                <li><a class="postcode" href="javascrip:void(0);" id="{{ $postcode->id }}">{{ $postcode->postcode }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>


        </nav>
    </div>
        <div class="col-md-7 mx-auto">
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
@endsection

@section('javascript')
    <script>
        var busstopsContainer = $("td#busstops > ul");
        var schoolsContainer = $("td#schools > ul");
        var addressesContainer = $("td#addresses > ul");
        $("a.postcode").on('click', function(e){
            e.preventDefault();
            let id = $(this).attr('id');
            console.log(id);
            loadPostcode(id);
        });

        function populateData(element, data){
            element.html('');
            data.forEach(function(item){
                element.append(`<li>${item.name}</li>`);
            });
        }

        function loadPostcode(id){
            axios.get(`/postcodes/${id}`)
                .then(function (response) {
                    populateData(busstopsContainer, response.data.busstops);
                    populateData(schoolsContainer, response.data.schools);

                    let addresses = [];
                    Object.keys(response.data.addresses).forEach(function(key) {
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