<?php

namespace {{App\}}Services;

use Crypto;
use {{App\}}Services\UserService;
use {{App\}}Repositories\Notification\NotificationRepository;

class NotificationService
{
    public function __construct(
        NotificationRepository $notificationRepository,
        UserService $userService
    ) {
        $this->repo = $notificationRepository;
        $this->userService = $userService;
    }

    public function all()
    {
        return $this->repo->all();
    }

    public function paginated()
    {
        return $this->repo->paginated(env('paginate', 25));
    }

    public function userBasedPaginated($id)
    {
        return $this->repo->userBasedPaginated($id, env('paginate', 25));
    }

    public function userBased($id)
    {
        return $this->repo->userBased($id);
    }

    public function search($input, $id)
    {
        return $this->repo->search($input, env('paginate', 25), $id);
    }

    public function notify($userId, $flag, $title, $details)
    {
        $input = [
            'user_id' => $userId,
            'flag' => $flag,
            'title' => $title,
            'details' => $details,
        ];
        $this->create($input);
    }

    public function create($input)
    {
        try {
            if ($input['user_id'] == 0) {
                $users = $this->userService->all();

                foreach ($users as $user) {
                    $input['uuid'] = Crypto::uuid();
                    $input['user_id'] = $user->id;
                    $this->repo->create($input);
                }

                return true;
            }

            $input['uuid'] = Crypto::uuid();
            return $this->repo->create($input);
        } catch (Exception $e) {
            throw new Exception("Could not send notifications please try agian.", 1);
        }
    }

    public function getUser($id)
    {
        return $this->userService->find($id);
    }

    public function find($id)
    {
        return $this->repo->find($id);
    }

    public function findByUuid($uuid)
    {
        return $this->repo->findByUuid($uuid);
    }

    public function update($id, $input)
    {
        return $this->repo->update($id, $input);
    }

    public function markAsRead($id)
    {
        $input['is_read'] = true;
        return $this->repo->update($id, $input);
    }

    public function destroy($id)
    {
        return $this->repo->destroy($id);
    }

    public function usersAsOptions()
    {
        $users = ['All' => 0];

        foreach ($this->userService->all() as $user) {
            $users[$user->name] = $user->id;
        }

        return $users;
    }

}