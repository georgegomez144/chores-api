<?php

/**
 * Created by PhpStorm.
 * User: George Gomez
 * Date: 3/30/2017
 * Time: 2:25 PM
 */
class Restlet_Core_Controller_Post
{
    private $_post;

    public function __construct()
    {
        $this->$_post = REST::post();
    }

    public function newMemberAction()
    {
        REST::json(
            $this->getModel()->saveNewMember(REST::post();
        );
    }

    public function newChoreAction()
    {
        $data = REST::post();
        REST::json(
            $this->getModel()->saveNewChore($this->$_post);
        );
    }

    public function newChoreStatusAction()
    {
        $data = REST::post();
        REST::json(
            $this->getModel()->saveNewChoreStatus($this->$_post);
        );
    }

    public function newMemberChoreDayAction()
    {
        $data = REST::post();
        REST::json(
            $this->getModel()->saveNewMemberChoreDay($this->$_post);
        );
    }

    private function getModel()
    {
        return REST::getModel('core');
    }
}
