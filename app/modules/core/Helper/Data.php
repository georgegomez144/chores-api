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

    public function saveNewMemberData($data)
    {
        return $this->insert('member', $data);
    }

    public function editMemberData($id, $data)
    {
        return $this->update('member', "member_id = {$id['id']}", $data);
    }

    public function deleteMemberData($id)
    {
        return $this->delete('member', "member_id = :id", $id);
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

    public function saveNewChoreData($data)
    {
        return $this->insert('chore', $data);
    }

    public function editChoreData($id, $data)
    {
        return $this->update('chore', "chore_id = {$id['id']}", $data);
    }

    public function deleteChoreData($id)
    {
        return $this->delete('chore', "chore_id = :id", $id);
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

    public function getChoreDaysData($id, $where = "")
    {
        $query = "
            SELECT DISTINCT
                day.*
            FROM day
            LEFT JOIN member_chore_day on member_chore_day.day_id = day.day_id
            WHERE member_chore_day.chore_id = :id {$where}
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

    public function getDayChoresData($id, $where = "")
    {
        $query = "
            SELECT DISTINCT
                chore.*, chore_status.*, member_chore_day.member_chore_day_id, member.*
            FROM chore
            LEFT JOIN member_chore_day on member_chore_day.chore_id = chore.chore_id
            LEFT JOIN chore_status on member_chore_day.member_chore_day_id = chore_status.member_chore_day_id
            LEFT JOIN member on member_chore_day.member_id = member.member_id
            WHERE member_chore_day.day_id = :id {$where}
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
        $filterQuery = '';
        if(array_key_exists('filterBy', $filter)) $filterQuery = $this->_checkFilterAndReturn('day', $filter);
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

    public function saveNewChoreStatusData($data)
    {
        return $this->insert('chore_status', $data);
    }

    public function editChoreStatusData($id, $data)
    {
        return $this->update('chore_status', "chore_status_id = {$id['id']}", $data);
    }

    public function deleteChoreStatusData($id)
    {
        return $this->delete('chore_status', "chore_status_id = :id", $id);
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

    public function saveNewMemberChoreDayData($data)
    {
        return $this->insert('member_chore_day', $data);
    }

    public function editMemberChoreDayData($id, $data)
    {
        return $this->update('member_chore_day', "member_chore_day_id = {$id['id']}", $data);
    }

    public function deleteMemberChoreDayData($id)
    {
        return $this->delete('member_chore_day', "member_chore_day_id = :id", $id);
    }








    private function _checkFilterAndReturn($table, $filter)
    {
        if (empty($filter)) return '';
        if (array_key_exists('filter', $filter)) return '';
        do {
            $filterCopy = $filter;
            unset($filterCopy['filterBy']);
            if (!$this->checkColumnExists($table, array_key_first($filterCopy))) {break;}
            return REST::queryFilter($table, $filter);
        } while(0);
    }
}
