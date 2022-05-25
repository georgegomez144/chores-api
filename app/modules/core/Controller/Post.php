<?php

/**
 * Created by PhpStorm.
 * User: George Gomez
 * Date: 3/30/2017
 * Time: 2:25 PM
 */
class Restlet_Core_Controller_Post
{

    public function newMemberAction()
    {
        REST::json(
            $this->getModel()->saveNewMember(REST::post())
        );
    }

    public function newChoreAction()
    {
        $data = REST::post();
        REST::json(
            $this->getModel()->saveNewChore(REST::post())
        );
    }

    public function newChoreStatusAction()
    {
        $data = REST::post();
        REST::json(
            $this->getModel()->saveNewChoreStatus(REST::post())
        );
    }

    public function newMemberChoreDayAction()
    {
        $data = REST::post();
        REST::json(
            $this->getModel()->saveNewMemberChoreDay(REST::post())
        );
    }

    private function getModel()
    {
        return REST::getModel('core');
    }
}
