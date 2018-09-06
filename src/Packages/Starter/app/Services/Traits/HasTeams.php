<?php

namespace {{App\}}Services\Traits;

trait HasTeams
{
    /**
     * Join a team
     *
     * @param  integer $teamId
     * @param  integer $userId
     * @return void
     */
    public function joinTeam($teamId, $userId)
    {
        $team = $this->team->find($teamId);
        $user = $this->model->find($userId);

        $user->teams()->attach($team);
    }

    /**
     * Leave a team
     *
     * @param  integer $teamId
     * @param  integer $userId
     * @return void
     */
    public function leaveTeam($teamId, $userId)
    {
        $team = $this->team->find($teamId);
        $user = $this->model->find($userId);

        $user->teams()->detach($team);
    }

    /**
     * Leave all teams
     *
     * @param  integer $teamId
     * @param  integer $userId
     * @return void
     */
    public function leaveAllTeams($userId)
    {
        $user = $this->model->find($userId);
        $user->teams()->detach();
    }
}
