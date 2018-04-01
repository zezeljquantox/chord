<?php

namespace Chord\Domain\User\Repositories;

use Chord\App\Repository;
use Chord\Domain\User\Models\User;
use Illuminate\Support\Facades\DB;

class UserCsvRepository extends Repository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getUsersDetails()
    {
        $users = DB::select(
            "SELECT u.id as id, CONCAT(u.name, ' ', u.surname) as full_name, h.id as house_id, 
	              CONCAT_WS(', ',a.postcode_id, NULLIF(a.district, ''),
                            NULLIF(a.locality, ''), NULLIF(a.street, ''), NULLIF(a.site, ''),
                            NULLIF(a.site_number,''),NULLIF(a.site_description, ''),
                            NULLIF(a.site_subdescription, '')) as full_address,
                  CASE h.propertytype 
                      WHEN 1 THEN 'flat'
                      WHEN 2 THEN 'small house'
                      WHEN 3 THEN 'big house'
                      WHEN 4 THEN 'villa'
                      ELSE '-'
                      END as property_type
            FROM users u INNER JOIN houses h ON u.id = h.user_id 
                INNER JOIN addresses as a ON h.address_id = a.id"
        );

        return collect($users)->groupBy('id');
    }

    public function geGivenLikes()
    {
        /*
         $users = DB::select(
            "SELECT count(*) as count, a as id, GROUP_CONCAT(b) as users FROM likes WHERE `like` = 1 GROUP BY a"
        );
        */
        $users = DB::table('likes')
            ->selectRaw('count(*) as count, a as id, GROUP_CONCAT(b) as users')
            ->where('like', 1)
            ->groupBy('a')
            ->get();
        return $users->groupBy('id');
    }

    /**
     * @return mixed
     */
    public function getReceivedLikes()
    {
        /*$users = DB::select(
            "SELECT count(*) as count, b as id FROM likes WHERE `like` = 1 GROUP BY b"
        );*/

        $users = DB::table('likes')
            ->selectRaw('count(*) as count, b as id')
            ->where('like', 1)
            ->groupBy('b')
            ->get();

        return $users->groupBy('id');
    }

    /**
     * @param int $olderThan
     */
    public function getPeople(int $olderThan = 0)
    {
        $query = DB::table('people')
            ->selectRaw('count(*) as count, user_id as id')
            ->where('age', '>', $olderThan)
            ->groupBy('user_id')
            ->get();

        return $query->groupBy('id');
    }

    public function getUserMatches()
    {
        $users = DB::select(
            "SELECT count(likes1.a) as count, likes1.a as id, GROUP_CONCAT(likes1.b) as user_list
             FROM likes as likes1 INNER JOIN likes as likes2 
                ON likes1.a =likes2.b AND likes1.b =likes2.a 
             WHERE likes1.`like` = 1 AND likes2.`like` = 1 
             GROUP BY likes1.a"
        );
        return collect($users)->groupBy('id');
    }

    public function getUserChats()
    {
        //$chats = DB::select("SELECT DISTINCT `from`, `to` FROM chats");
        $chats = DB::table('chats')->select('from', 'to')->distinct()->get();

        $result = [];

        foreach ($chats as $chat){
            if(!isset($result[$chat->from]['all'][$chat->to])){
                $result[$chat->from]['all'][$chat->to] = 1;
                $result[$chat->from]['all']['count'] = !empty($result[$chat->from]['all']['count']) ? ++$result[$chat->from]['all']['count'] : 1;
            }
            if(!isset($result[$chat->to]['all'][$chat->from])){
                $result[$chat->to]['all'][$chat->from] = 1;
                $result[$chat->to]['all']['count'] = !empty($result[$chat->to]['all']['count']) ? ++$result[$chat->to]['all']['count'] : 1;
            }

            if(!isset($result[$chat->to]['unanswered'][$chat->from])){
                $result[$chat->from]['unanswered'][$chat->to] = 1;
            } else {
                unset($result[$chat->to]['unanswered'][$chat->from]);
            }
        }

        return $result;
    }
}