@extends('layouts.app')

@section('content')

    <div class="container mt-1">
        <div class="row">
        <div class="col-md-8" data-spy="affix">
        @foreach ($houses as $house)
            <div class="row">
                <div class="col-md-12">
            <div id="{{ $house->user->id }}" data-house-id="{{ $house->id }}" class="card mb-3 house">
                <div class="card-body">
                    <h5 class="card-title">
                        {{ $house->address->print() }}
                    </h5>
                    <h6 class="card-subtitle mb-2 text-muted">
                        Tenant: {{ $house->user->name }}
                    </h6>
                    @if (!empty($mapedReactions[$house->user->id]['likedByOther']))
                        <p data-info="likedByOther" class="card-text">User has liked your house</p>
                    @else
                        <p data-info="likedByOther" class="card-text"></p>
                    @endif
                    <a href="javascript:void(0);" class="btn {{ $mapedReactions[$house->user->id]['like'] ?? 'btn-default' }} btn-like"><i class="far fa-thumbs-up"></i></a>
                    <a href="javascript:void(0);" class="btn {{ $mapedReactions[$house->user->id]['dislike'] ?? 'btn-default' }} btn-dislike"><i class="far fa-thumbs-down"></i></a>
                </div>
            </div>
                </div>
                <!--div class="col-md-4">
                    <div class="card mb-3">
                        <div class="h-100 card-body">
                            <input class="form-control form-control-sm" type="text" placeholder="write a message">
                        </div>
                </div-->
            </div>

        @endforeach
        {{ $houses->links() }}
        </div>
        <div class="moja col-md-2 card">
            <div class="h-50 d-inline-block scroll-box">
                <p>"Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?"</p>
            </div>
            <div class="h-50 d-inline-block scroll-box">
                <div class="input-group align-text-bottom">
                    <input type="text" class="form-control" placeholder="Username" aria-describedby="basic-addon1">
                </div>
            </div>

    </div>
        </div>
    </div>

@endsection

@section('javascript')

        <script src="http://chord.test:3000/socket.io/socket.io.js"></script>
    <script>
        var socket = io("http://chord.test:3000", { query: "user={{ Auth::user()->id }}&name={{ Auth::user()->name }}" });
        socket.on('connect', () => {
            console.log('Connected to server');
            socket.emit('getUserList', {}, function (data) {
                console.log(data);
            });
        });

        socket.on('UserReacted', (data) => {
            if(data.like == 1) {
                $("div#" + data.user).find(`[data-info='likedByOther']`).text("User has liked your house");
            } else {
                $("div#" + data.user).find(`[data-info='likedByOther']`).text("");
            }
        });

        socket.on('disconnect', () => {
            console.log('Disconnected from server');
        });

        $(".btn-like, .btn-dislike").on('click', function(){
            var element = $(this);
            let id = element.parents(".house").attr('data-house-id');
            if(element.hasClass("btn-success") || element.hasClass("btn-danger")){
                removeReaction(element, id);
                return;
            }

           if($(this).hasClass('btn-like')){
               var action = 1;
           }
            if($(this).hasClass('btn-dislike')){
                var action = 0;
            }

           console.log(id);
            reactionAdd(element, id, action);
        });

        function reactionAdd(element, house, action){
            axios.post('/reaction', {
                house: house,
                action: action
            })
            .then(function (response) {
                if(response.data.like){
                    element.addClass('btn-success');
                    element.siblings('.btn-dislike').removeClass('btn-danger');
                    return;
                }
                element.addClass('btn-danger');
                element.siblings('.btn-like').removeClass('btn-success');
            })
            .catch(function (error) {
                console.log(error);
            });
        }

        function removeReaction(element, house){
            axios.delete('/reaction', { data: {  house: house }})
            .then(function (response){
                if(response.data.status == 'ok'){
                    element.removeClass('btn-success btn-danger');
                }
            })
            .catch(function (error) {
                console.log(error);
            });
        }
    </script>
@endsection