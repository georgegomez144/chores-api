<?php

/**
 * Created by PhpStorm.
 * User: George Gomez
 * Date: 3/30/2017
 * Time: 2:31 PM
 */
class Restlet_Core_Helper_Data
    extends Database
{
    public function getMemberData($id)
    {
        $members = $this->select("
            SELECT DISTINCT * FROM member
            WHERE member.member_id = :id
        ", $id);
        return $members;
    }


    public function getMembersData($filter)
    {
        $filterQuery = $this->_checkFilterAndReturn('member', $filter);
        $query = "
            SELECT DISTINCT * FROM member
            {$filterQuery}
        ";
        return $this->select($query);
    }

    public function getMemberChoresData($id)
    {
        $query = "
            SELECT DISTINCT
                chore.*
            FROM chore
            LEFT JOIN member_chore_day on member_chore_day.chore_id = chore.chore_id
            WHERE member_chore_day.member_id = :id
        ";
        return $this->select($query, $id);
    }

    public function getMemberChoresDaysData($id)
    {
        $query = "
            SELECT DISTINCT
                day.*
            FROM day
            LEFT JOIN member_chore_day on member_chore_day.day_id = day.day_id
            WHERE member_chore_day.member_id = :member_id
            AND member_chore_day.chore_id = :chore_id
        ";
        return $this->select($query, $id);
    }

    public function getChoreData($id)
    {
        $chore = $this->select("
            SELECT * FROM chore
            WHERE chore.chore_id = :id
        ", $id);
        return $chore;
    }

    public function getChoresData($filter)
    {
        $filterQuery = $this->_checkFilterAndReturn('chore', $filter);
        $query = "
            SELECT * FROM chore
            {$filterQuery}
        ";
        return $this->select($query);
    }

    public function getChoreDaysData($id)
    {
        $query = "
            SELECT DISTINCT
                day.*
            FROM day
            LEFT JOIN member_chore_day on member_chore_day.day_id = day.day_id
            WHERE member_chore_day.chore_id = :id
        ";
        return $this->select($query, $id);
    }

    public function getChoreDayMembersData($ids)
    {
        $query = "
            SELECT DISTINCT
                member.*
            FROM member
            LEFT JOIN member_chore_day on member_chore_day.member_id = member.member_id
            WHERE member_chore_day.chore_id = :chore_id
            AND member_chore_day.day_id = :day_id
        ";
        return $this->select($query, $ids);
    }

    public function getDayChoresData($id)
    {
        $query = "
            SELECT DISTINCT
                chore.*
            FROM chore
            LEFT JOIN member_chore_day on member_chore_day.chore_id = chore.chore_id
            WHERE member_chore_day.day_id = :id
        ";
        return $this->select($query, $id);
    }

    public function getDayData($id)
    {
        $day = $this->select("
            SELECT * FROM day
            WHERE day_id = :id
        ", $id);
        return $day;
    }

    public function getDaysData($filter)
    {
        $filterQuery = $this->_checkFilterAndReturn('day', $filter);
        $query = "
            SELECT * FROM day
            {$filterQuery}
        ";
        return $this->select($query);
    }

    public function getChoreStatus($filter)
    {
        $filterQuery = $this->_checkFilterAndReturn('day', $filter);
        $query = "
            SELECT * FROM chore_status
            {$filterQuery}
        ";
        return $this->select($query);
    }

    public function getMemberChoreDayData($filter)
    {
        $filterQuery = $this->_checkFilterAndReturn('member_chore_day', $filter);
        $query = "
            SELECT * FROM member_chore_day
            {$filterQuery}
        ";
        return $this->select($query);
    }








    private function _checkFilterAndReturn($table, $filter)
    {
        if (empty($filter)) return '';
        do {
            $filterCopy = $filter;
            unset($filterCopy['filterBy']);
            if (!$this->checkColumnExists($table, array_key_first($filterCopy))) {break;}
            return REST::queryFilter($filter);
        } while(0);
    }
}
