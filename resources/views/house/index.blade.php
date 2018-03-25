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
        <div id="user-list" class="moja col-md-2 card">
            <div class="h-50 px-0 d-inline-block scroll-box">
                <div class="list-group">
                </div>
                <!--p>"Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?"</p-->
            </div>
            <div class="h-50 d-inline-block scroll-box">
                <input type="text" id="message" class="form-control" placeholder="Username" aria-describedby="basic-addon1">
                <div id="chat">

                </div>
            </div>


    </div>
        </div>
    </div>

@endsection

@section('javascript')

        <script src="http://chord.test:3000/socket.io/socket.io.js"></script>
    <script>
        var userTo = 0;
        var messageContainer = $("div#chat");
        $("#user-list").on('click', "a.list-group-item", function (){
            console.log($(this).attr('id'));
            $("a.list-group-item").removeClass("active");
            $(this).addClass("active");
            userTo = $(this).attr('id');
            messageContainer.html("");
            getChat(userTo);
        });

        var socket = io("http://chord.test:3000", { query: "user={{ Auth::user()->id }}&name={{ Auth::user()->name }}" });
        socket.on('connect', () => {
            console.log('Connected to server');
            $("#user-list .list-group").html("");
            socket.emit('getUserList', {}, function (data) {
                data.forEach(function (item){
                    addUserToList(item);
                });
                //console.log(data);
            });
        });

        socket.on('UserReacted', (data) => {
            if(data.like == 1) {
                $("div#" + data.user).find(`[data-info='likedByOther']`).text("User has liked your house");
            } else {
                $("div#" + data.user).find(`[data-info='likedByOther']`).text("");
                console.log(data.user);
                removeUserFromChat(data.user);
            }
        });

        socket.on('NewUserForChat', (data) => {
            addUserToList(data);
        });

        socket.on('userAvailable', (data) => {
            $("#user-list .list-group").find("a#id"+data.id).prepend("<i class='fas fa-circle'></i>&nbsp;");
        });

        function addUserToList(data){
            if(data.active == 1){
                $("#user-list .list-group").append(`<a href="javascript:void(0);" id="${data.id}" class="list-group-item"><i class="fas fa-circle"></i>&nbsp;<span>${data.name}</span></a>`);
                return;
            }
            $("#user-list .list-group").append(`<a href="javascript:void(0);" id="${data.id}" class="list-group-item"><span>${data.name}</span></a>`);
        }

        socket.on('userDisconnected', (data) => {
            removeUserFromChat(data.id);
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
                    removeUserFromChat(response.data.user);
                }
            })
            .catch(function (error) {
                console.log(error);
            });
        }

        $("input#message").on('keypress', function(e){
            if(e.which == 13){
                console.log(userTo);
                let message = $(this).val().replace(/(<([^>]+)>)/ig,"").trim();
                console.log(message);
                if(message.length <= 0 || userTo == 0){
                    return;
                }
                socket.emit('sendMessage', {to : userTo, message: message}, function (data) {
                    console.log(data);
                    if(userTo == data.to){
                        showMessage(data.from, data.message);
                    }
                });
            }
        });

        socket.on("newMessage", (data) => {
            console.log(data);
            console.log('da li su isti', userTo == data.from, data.from, userTo);
            if(userTo == data.from){
                showMessage(data.from, data.message);
            }
        });

        function removeUserFromChat(id){
            let element = $("#user-list .list-group").find("a#"+id);
            if(element.hasClass("active")){
                userTo = 0;
                $("div.chat p").remove();
            }
            $("#user-list .list-group").find("a#"+id).remove();
        }

        function getChat(userId){
            axios.get('/chat?user='+userId)
                .then(function (response) {
                    console.log(response.data.chat[0]);
                    response.data.chat.forEach(function (item){
                        console.log('item je', item);
                        showMessage(item.from, item.message);
                    });
                })
                .catch(function (error) {
                    console.log(error);
                });
        }

        function showMessage(user, message){
            let currentUser = {{ Auth::user()->id }};
            console.log(user, currentUser, user != currentUser);
            let from = "You";
            if(user != currentUser){
                from = $("#user-list .list-group").find("a#"+user).text();
                console.log(from);
            }
            console.log(`<p>${from}: ${message}</p>`);
            messageContainer.append(`<p>${from}: ${message}</p>`);

        }
    </script>
@endsection