@extends('layouts.app')

@section('css')
    <style>
        .chat-wrapper { height: calc(100% - 60px); margin-bottom: 10px; }

        .right-sidebar{
            position: fixed;
            right: 10px;
            height: calc(100% - 100px);
            padding: 15px;
        }

        .scroll-box {
            overflow-y: auto;
            padding: 0 1rem
        }
    </style>
    @endsection
@section('content')

    <div class="container mt-1">
        <div class="row">
        <div class="col-md-8" data-spy="affix">
        @foreach ($houses as $house)
            @include('house.partials.house', ['house' => $house])
        @endforeach
        {{ $houses->links() }}
        </div>
        <div id="user-list" class="right-sidebar col-md-2 card">
            <div class="h-50 px-0 d-inline-block scroll-box">
                <div class="list-group">
                </div>
            </div>
            <div class="h-50 d-inline-block">
                <div class="chat-wrapper scroll-box">
                    <div id="chat"></div>
                </div>
                <input type="text" id="message" class="form-control" placeholder="Type a message" aria-describedby="basic-addon1">
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
            $("a.list-group-item").removeClass("active");
            $(this).addClass("active");
            userTo = $(this).attr('id');
            messageContainer.html("");
            getChat(userTo);
        });

        var nodeServer = "{{ env('APP_NODE') }}";

        var socket = io(nodeServer, { query: "user={{ Auth::user()->id }}&name={{ Auth::user()->name }}" });
        socket.on('connect', () => {
            console.log('Connected to server');
            $("#user-list .list-group").html("");
            socket.emit('getUserList', {}, function (data) {
                data.forEach(function (item){
                    addUserToList(item);
                });
            });
        });

        socket.on('UserReacted', (data) => {
            if(data.like == 1) {
                $("div#" + data.user).find(`[data-info='likedByOther']`).text("User has liked your house");
            } else {
                $("div#" + data.user).find(`[data-info='likedByOther']`).text("");
                removeUserFromChat(data.user);
            }
        });

        socket.on('NewUserForChat', (data) => {
            addUserToList(data);
            showSwapButton(data.id);
        });

        function showSwapButton(id){
            if($('div#'+id) !== undefined) {
                $('div#' + id).find('h5.card-title')
                    .append("<a href='javascript:void(0);' class='btn btn-default btn-info float-right swap-house' title='Swap house'><i class='fas fa-exchange-alt'></i></a>");
            }
        }

        $('div.house').on('click', 'a.swap-house', function(e){
            e.preventDefault();
            let houseElement = $(this).parents("div.house");
            let userToSwap = houseElement.attr('id');

            axios.put('/houses/swap', {
                user: userToSwap
            })
            .then(function (response) {
                houseElement.find('.card-body .card-title strong').text(response.data.address);
            })
            .catch(function (error) {
                console.log(error);
            });
        });

        function dismissSwapButton(id){
            let element = $('div#'+id);
            if(element !== undefined){
                element.find('h5.card-title a').remove();
            }
        }

        socket.on('userAvailable', (data) => {
            $("#user-list .list-group").find("a#"+data.id).prepend("<i class='fas fa-circle'></i>&nbsp;");
        });

        function addUserToList(data){
            if(data.active == 1){
                $("#user-list .list-group").append(`<a href="javascript:void(0);" id="${data.id}" class="list-group-item"><i class="fas fa-circle"></i>&nbsp;<span>${data.name}</span></a>`);
                return;
            }
            $("#user-list .list-group").append(`<a href="javascript:void(0);" id="${data.id}" class="list-group-item"><span>${data.name}</span></a>`);
        }

        function putUserOffline(id){
            let element = $("#user-list .list-group #"+id);
            let content = element.html();
            if(content === undefined){
                return;
            }
            let index = content.indexOf("<span>");
            let newValue = content.substr(index, content.length);
            element.html(newValue);
        }

        socket.on('userDisconnected', (data) => {
            putUserOffline(data.id);
            //removeUserFromChat(data.id);
        });

        socket.on('UserSwappedHouse', (data) => {
            let element = $('div#'+data.user);
            if(element === undefined){
                return;
            }
            element.find('.card-body .card-title strong').text(data.address)
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
            reactionAdd(element, id, action);
        });

        function reactionAdd(element, house, action){
            axios.post('/reactions', {
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
            axios.delete('/reactions', { data: {  house: house }})
            .then(function (response){
                if(response.data.status == 'ok'){
                    element.removeClass('btn-success btn-danger');
                    removeUserFromChat(response.data.user);
                    socket.emit('removeUserFromChat', response.data.user);
                }
            })
            .catch(function (error) {
                console.log(error);
            });
        }

        socket.on("UserRemovedReaction", (response) => {
            let element = $("div#"+response.id);
            if(element !== undefined){
                element.find('p[data-info="likedByOther"]').text("");
                removeUserFromChat(response.id);
            }

        });

        $("input#message").on('keypress', function(e){
            if(e.which == 13){
                let message = $(this).val().replace(/(<([^>]+)>)/ig,"").trim();
                if(message.length <= 0 || userTo == 0){
                    return;
                }
                socket.emit('sendMessage', {to : userTo, message: message}, function (data) {
                    if(userTo == data.to){
                        showMessage(data.from, data.message);
                        $('#message').val("");
                        var d = $('#chat');
                        d.scrollTop(d.prop("scrollHeight"));
                    }
                });
            }
        });

        socket.on("newMessage", (data) => {
            if(userTo == data.from){
                showMessage(data.from, data.message);
            }
        });

        function removeUserFromChat(id){
            let element = $("#user-list .list-group").find("a#"+id);
            if(element === undefined){
                return;
            }
            if(element.hasClass("active")){
                userTo = 0;
                $("div.chat p").remove();
            }
            element.remove();

            dismissSwapButton(id);
        }

        function getChat(userId){
            axios.get('/chats?user='+userId)
                .then(function (response) {
                    response.data.chat.forEach(function (item){
                        showMessage(item.from, item.message);
                    });
                })
                .catch(function (error) {
                    console.log(error);
                });
        }

        function showMessage(user, message){
            let currentUser = {{ Auth::user()->id }};
            let from = "You";
            if(user != currentUser){
                from = $("#user-list .list-group").find("a#"+user).text();
            }
            messageContainer.append(`<p>${from}: ${message}</p>`);

        }
    </script>
@endsection