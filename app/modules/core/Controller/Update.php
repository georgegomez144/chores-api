<?php

/**
 * Created by PhpStorm.
 * User: George Gomez
 * Date: 3/30/2017
 * Time: 2:25 PM
 */
class Restlet_Core_Controller_Update
{

    public function editMemberAction($id)
    {
        $data = REST::post();
        REST::json(
            $this->getModel()->editMember($id, $data)
        );
    }

    public function editChoreAction($id)
    {
        $data = REST::post();
        REST::json(
            $this->getModel()->editChore($id, $data)
        );
    }

    public function editChoreStatusAction($id)
    {
        $data = REST::post();
        REST::json(
            $this->getModel()->editChoreStatus($id, $data)
        );
    }

    public function editMemberChoreDayAction($id)
    {
        $data = REST::post();
        REST::json(
            $this->getModel()->editMemberChoreDay($id, $data)
        );
    }




    private function getModel()
    {
        return REST::getModel('core');
    }
}
