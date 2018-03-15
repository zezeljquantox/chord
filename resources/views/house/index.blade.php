@extends('layouts.app')

@section('content')

    <div class="container">
        @foreach ($houses as $house)
            <div class="row">
                <div class="col-md-8">
            <div id="{{ $house->id }}" class="card mb-3 house">
                <div class="card-body">
                    <h5 class="card-title">
                        {{ $house->address->print() }}
                    </h5>
                    <h6 class="card-subtitle mb-2 text-muted">
                        Tenant: {{ $house->user->name }}
                    </h6>
                    @if (!empty($mapedReactions[$house->user->id]['likedByOther']))
                        <p class="card-text">User has liked your house</p>
                    @endif
                    <a href="javascript:void(0);" class="btn {{ $mapedReactions[$house->user->id]['like'] ?? 'btn-default' }} btn-like"><i class="far fa-thumbs-up"></i></a>
                    <a href="javascript:void(0);" class="btn {{ $mapedReactions[$house->user->id]['dislike'] ?? 'btn-default' }} btn-dislike"><i class="far fa-thumbs-down"></i></a>
                </div>
            </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="h-100 card-body">
                            <input class="form-control form-control-sm" type="text" placeholder="write a message">
                        </div>
                </div>
            </div>
            </div>
        @endforeach

        {{ $houses->links() }}
    </div>
@endsection

@section('javascript')
    <script>
        $(".btn-like, .btn-dislike").on('click', function(){
            var element = $(this);
            let id = element.parents(".house").attr('id');
            if(element.hasClass("btn-success") || element.hasClass("btn-danger")){
                return;
            }
           if($(this).hasClass("btn-success")){
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
    </script>
@endsection