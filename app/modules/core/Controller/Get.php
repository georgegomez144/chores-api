<?php

/**
 * Created by PhpStorm.
 * User: George Gomez
 * Date: 3/30/2017
 * Time: 2:25 PM
 */
class Restlet_Core_Controller_Get
{
    public function memberAction($params)
    {
        $data = $this->getModel()->getMember(["id" => $params['id']]);
        REST::json($data);
    }

    public function membersAction($params)
    {
        $data = $this->getModel()->getMembers($params);
        REST::json($data);
    }

    public function choreAction($params)
    {
        $data = $this->getModel()->getChore(["id" => $params['id']]);
        REST::json($data);
    }

    public function choresAction($params)
    {
        $data = $this->getModel()->getChores($params);
        REST::json($data);
    }

    public function dayAction($params)
    {
        $data = $this->getModel()->getDay($params);
        REST::json($data);
    }

    public function daysAction($params)
    {
        $data = $this->getModel()->getDays($params);
        REST::json($data);
    }







    private function getModel()
    {
        return REST::getModel('core');
    }
}
