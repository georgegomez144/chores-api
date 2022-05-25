<?php

/**
 * Created by PhpStorm.
 * User: George Gomez
 * Date: 3/30/2017
 * Time: 2:25 PM
 */
class Restlet_Core_Controller_Delete
{

    public function deleteMemberAction($id)
    {
        REST::json(
            $this->getModel()->deleteMember($id)
        );
    }

    public function deleteChoreAction($id)
    {
        REST::json(
            $this->getModel()->deleteChore($id)
        );
    }

    public function deleteChoreStatusAction($id)
    {
        REST::json(
            $this->getModel()->deleteChoreStatus($id)
        );
    }

    public function deleteMemberChoreDayAction($id)
    {
        REST::json(
            $this->getModel()->deleteMemberChoreDay($id)
        );
    }




    private function getModel()
    {
        return REST::getModel('core');
    }
}
