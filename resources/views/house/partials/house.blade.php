<div class="row">
    <div class="col-md-12">
        <div id="{{ $house->user->id }}" data-house-id="{{ $house->id }}" class="card mb-3 house">
            <div class="card-body">
                <h5 class="card-title">
                    <strong>{{ $house->address->full_address }}</strong>
                    @if(!empty($mapedReactions[$house->user->id]['likedByOther']) && !empty($mapedReactions[$house->user->id]['like']))
                        <a href='javascript:void(0);' class='btn btn-default btn-info float-right swap-house'
                           title='Swap house'><i class='fas fa-exchange-alt'></i></a>
                    @endif
                </h5>

                <h6 class="card-subtitle mb-2 text-muted">
                    Tenant: {{ $house->user->name }}
                </h6>
                @if (!empty($mapedReactions[$house->user->id]['likedByOther']))
                    <p data-info="likedByOther" class="card-text">User has liked your house</p>
                @else
                    <p data-info="likedByOther" class="card-text"></p>
                @endif
                <a href="javascript:void(0);"
                   class="btn {{ $mapedReactions[$house->user->id]['like'] ?? 'btn-default' }} btn-like"><i
                            class="far fa-thumbs-up"></i></a>
                <a href="javascript:void(0);"
                   class="btn {{ $mapedReactions[$house->user->id]['dislike'] ?? 'btn-default' }} btn-dislike"><i
                            class="far fa-thumbs-down"></i></a>
            </div>
        </div>
    </div>

</div>
