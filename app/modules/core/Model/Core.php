<?php

/**
 * Created by PhpStorm.
 * User: George Gomez
 * Date: 3/30/2017
 * Time: 2:38 PM
 */
class Restlet_Core_Model_Core extends Restlet_Core_Helper_Data
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getMember($id)
    {
        $memberData = $this->getMemberData($id);
        $memberData['chores'] = $this->getMemberChoresData($id);
        foreach($memberData['chores'] as $key => $chore)
        {
            $memberData['chores'][$key]['days'] = $this->getMemberChoresDaysData([
                "member_id" => $id['id'],
                "chore_id"  => $chore['chore_id']
            ]);
        }
        return $memberData;
    }

    public function saveNewMember($data)
    {
        return $this->saveNewMemberData($data);
    }

    public function editMember($id, $data)
    {
        return $this->editMemberData($id, $data);
    }

    public function deleteMember($id)
    {
        return $this->deleteMemberData($id);
    }

    public function getMembers($filter)
    {
        $members = $this->getMembersData($filter);
        if (array_key_first($members) == 0)
        {
            foreach ($members as $key => $member)
            {
                $members[$key]['chores'] = $this->getMemberChoresData(['id' => $member['member_id']]);
                foreach($members[$key]['chores'] as $cKey => $chore)
                {
                    $members[$key]['chores'][$cKey]['days'] = $this->getMemberChoresDaysData([
                        "member_id" => $member['member_id'],
                        "chore_id"  => $chore['chore_id']
                    ]);
                }
            }
        } else {
            $members['chores'] = $this->getMemberChoresData(['id' => $member['member_id']]);
            foreach($members['chores'] as $cKey => $chore)
            {
                $members['chores'][$cKey]['days'] = $this->getMemberChoresDaysData([
                    "member_id" => $member['member_id'],
                    "chore_id"  => $chore['chore_id']
                ]);
            }
        }
        return $members;
    }

    public function getChore($id)
    {
        $chore = $this->getChoreData($id);
        $chore['days'] = $this->getChoreDaysData($id);
        if (array_key_first($chore['days']) == 0)
        {
            foreach($chore['days'] as $key => $day)
            {
                $chore['days'][$key]['members'] = $this
                    ->getChoreDayMembersData([
                        'day_id'    => $day['day_id'],
                        'chore_id'  => $id['id']
                    ]);
            }
        } else
        {
            $chore['days']['members'] = $this
                ->getChoreDayMembersData([
                    'day_id'    => $chore['days']['day_id'],
                    'chore_id'  => $id['id']
                ]);
        }
        return $chore;
    }

    public function saveNewChore($data)
    {
        return $this->saveNewChoreData($data);
    }

    public function editChore($id, $data)
    {
        return $this->editChoreData($id, $data);
    }

    public function deleteChore($id)
    {
        return $this->deleteChoreData($id);
    }

    public function getChores($filter)
    {
        $chores = $this->getChoresData($filter);

        $postFilter = array_key_exists('filter', $filter);

        $filterBy = $filter['filter'];
            unset($filter['filter']);
        $whereKey = array_key_first($filter);
        $whereValue = $filter[$whereKey];

        foreach ($chores as $key => $chore)
        {
            $where = '';
            if($postFilter && $filterBy === 'days') $where = "AND day.{$whereKey} = {$whereValue}";
            $chores[$key]['days'] = $this->getChoreDaysData(['id' => $chore['chore_id']], $where);

            if (array_key_first($chores[$key]['days']) == 0)
            {
                foreach($chores[$key]['days'] as $dKey => $day)
                {
                    $chores[$key]['days'][$dKey]['members'] = $this
                        ->getChoreDayMembersData([
                            'day_id'    => $day['day_id'],
                            'chore_id'  => $chore['chore_id']
                        ]);
                }
            } else
            {
                $chores[$key]['days']['members'] = $this
                    ->getChoreDayMembersData([
                        'day_id'    => $chores[$key]['days']['day_id'],
                        'chore_id'  => $chore['chore_id']
                    ]);
            }
        }
        return $chores;
    }

    public function getDay($id)
    {
        $day = $this->getDayData($id);
        $day['chores'] = $this->addChoresToDays($day);
        return $day;
    }

    public function getDays($filter)
    {
        $days = $this->getDaysData($filter);
        foreach ($days as $key => $day)
        {
            $days[$key]['chores'] = $this->addChoresToDays($day);
        }
        return $days;
    }

    private function addChoresToDays($day)
    {
        $chores = $this->getDayChoresData(['id' => $day['day_id']]);
        //REST::toScreen($chores);
        if (array_key_first($chores) == 0)
        {
            foreach($chores as $cKey => $chore)
            {
                $chores[$cKey]['members'] = $this
                    ->getChoreDayMembersData([
                        'day_id'    => $day['day_id'],
                        'chore_id'  => $chore['chore_id']
                    ]);
            }
        } else {
            $chores['members'] = $this
                ->getChoreDayMembersData([
                    'day_id'    => $day['day_id'],
                    'chore_id'  => $chore['chore_id']
                ]);
        }
        return $chores;
    }

    public function saveNewChoreStatus($data)
    {
        return $this->saveNewChoreStatusData($data);
    }

    public function editChoreStatus($id, $data)
    {
        return $this->editChoreStatusData($id, $data);
    }

    public function deleteChoreStatus($id)
    {
        return $this->deleteChoreStatusData($id);
    }

    public function saveNewMemberChoreDay($data)
    {
        return $this->saveNewMemberChoreDayData($data);
    }

    public function editMemberChoreDay($id, $data)
    {
        return $this->editMemberChoreDayData($id, $data);
    }

    public function deleteMemberChoreDay($id)
    {
        return $this->deleteMemberChoreDayData($id);
    }
}
