<?php

namespace Chord\Domain\User\Services;

use Chord\App\Service;
use Chord\App\Traits\FileName;
use Chord\Domain\User\Repositories\UserCsvRepository;
use Illuminate\Contracts\Filesystem\Factory as Storage;

class UserCsvService extends Service
{
    use FileName;

    private $storage;

    /**
     * UserCsvService constructor.
     * @param Storage $storage
     * @param UserCsvRepository $repository
     */
    public function __construct(Storage $storage, UserCsvRepository $repository)
    {
        parent::__construct($repository);
        $this->storage = $storage;
    }

    /**
     * @param string $path
     * @return string
     */
    public function generateCsv($path = '')
    {
        $filePath = $this->storage->disk('reports')->getAdapter()->getPathPrefix();
        $filename = $this->uniqueFilename('reports', $path.time().'.csv');
        $file = fopen($filePath.$filename, 'w');
        $this->createHeader($file);

        $userDetails = $this->repository->getUsersDetails();

        $givenLikes = $this->repository->geGivenLikes();
        $receivedLikes = $this->repository->getReceivedLikes();
        $matches = $this->repository->getUserMatches();
        $userChats = $this->repository->getUserChats();
        $numberOfPeople = $this->repository->getPeople();
        $numberOfOldPeople = $this->repository->getPeople(45);

        foreach ($userDetails as $userId => $user){
            $row = [];
            $row[] = $userId;
            $row[] = $user[0]->full_name;
            $row[] = $user[0]->house_id;
            $row[] = $user[0]->property_type;
            $row[] = $user[0]->full_address;
            $row[] = $givenLikes[$userId][0]->count ?? '';
            $row[] = $givenLikes[$userId][0]->users ?? '';
            $row[] = $receivedLikes[$userId][0]->count ?? '';
            $row[] = $matches[$userId][0]->count ?? '';
            $row[] = $matches[$userId][0]->user_list ?? '';
            $row[] = $userChats[$userId]['all']['count'] ?? '';
            $row[] = !empty($userChats[$userId]['unanswered']) ? count($userChats[$userId]['unanswered']) : '';
            $row[] = $numberOfPeople[$userId][0]->count ?? '';
            $row[] = $numberOfOldPeople[$userId][0]->count ?? '';
            fputcsv($file, $row,';');
        }
        fclose($file);
        return $path.$filename;
    }

    /**
     * @param $file
     */
    private function createHeader($file)
    {
        $columns = ['user id', 'full name', 'house id', 'property type', 'full address', 'number of likes given',
                    'like ids', 'number of likes received', 'number of matches', 'match ids', 'number of chats',
                    'number of unanswered messages', 'number of people', 'number of old men'];
        fputcsv($file, $columns,';');
    }

    /**
     * @return string
     */
    public function getCsv()
    {
        $filename = $this->generateCsv();
        return $this->storage->disk('reports')->getAdapter()->getPathPrefix().$filename;
    }

    /**
     * @return bool|string
     */
    public function isExistsCronCsv()
    {
        $files = $this->storage->disk('reports')->files('cron');

        if(!empty($files)){
            rsort($files);
            return $this->storage->disk('reports')->getAdapter()->getPathPrefix().$files[0];
        }
        return false;
    }

}