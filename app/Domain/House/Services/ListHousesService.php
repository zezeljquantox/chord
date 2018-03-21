<?php

namespace Chord\Domain\House\Services;

use Chord\App\Service;
use Chord\Domain\House\Repositories\HouseRepository;
use Illuminate\Database\Eloquent\Collection;
use Facades\Chord\Domain\User\Repositories\ReactionRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ListHousesService extends Service
{
    public function __construct(HouseRepository $repository)
    {
        parent::__construct($repository);
    }

    public function getHouses(int $userId, int $numOfItems = 20): LengthAwarePaginator
    {
        return $this->repository->load($userId, $numOfItems);
    }

    public function getReactions($userId, array $userIds): Collection
    {
       return ReactionRepository::getUsersReaction($userId, $userIds);
    }

    //ne koristi se
    public function getHousesWithReactions(int $userId)
    {
        $houses = $this->getHouses();
        //dd($houses->items());
        $userIds = $houses->pluck('user_id')->toArray();

        $reactions = $this->getReactions($userId, $userIds);
        //dd($reactions);
        $res = $this->mergeHousesAndReactions($houses->items(), $reactions);
        dd($res);

    }

    public function mapHousesReactions($userId, $reactions)
    {
        $mapedReactions = [];

        $reactions->each(function ($reaction) use ($userId, &$mapedReactions) {
            if($userId == $reaction->a){
                if($reaction->like){
                    $mapedReactions[$reaction->b]['like'] = 'btn-success';
                } else {
                    $mapedReactions[$reaction->b]['dislike'] = 'btn-danger';
                }
            } elseif ($userId == $reaction->b){
                $mapedReactions[$reaction->a]['likedByOther'] = $reaction->like ? 'btn-success' : '';
            }
        });
        /*
        foreach ($houses->items() as $key => $house){
            $reactions->each(function ($reaction) use ($house, $mapedReactions) {
                var_dump('q',$house->user->id, 'w',$reaction->a, 'e',$reaction->b);
                if($house->user->id == $reaction->a){
                    $mapedReactions[$house->id]['like'] = $reaction->like ? 'btn-success' : 'btn-danger';
                } elseif ($house->user->id == $reaction->b){
                    $mapedReactions[$house->id]['likedByOther'] = $reaction->like ? 'btn-success' : '';
                }
            });
        }
        */
        /*
        $houses->items()->each(function ($house, $key) use ($houses, $reactions) {
            $reactions->each(function ($reaction) use ($houses, $key) {

                if($houses[$key]->user->id == $reaction->a){

                   $like = $reaction->like ? 'btn-success' : 'btn-danger';
                   $houses[$key]->put('like', $like);
                } elseif ($houses[$key]->user->id == $reaction->b){
                    $likedByOther = $reaction->like ? 'btn-success' : '';
                    $houses[$key]->put('likedByOther', $likedByOther);
                }
            });
        });
*/
        return $mapedReactions;
    }
}