<?php

class StatsService extends Service {
    
    public function __construct() {
        parent::__construct();
    }

    public function getStats($input) {
        if ($input->action == 'referrals') {
            return $this->getReferralsStats($input);
        }

        if ($input->action == 'ratings') {
            return $this->getRatingsStats($input);
        }

        if ($input->action == 'all') {
            $ratings = [];
            $referrals = $this->getReferralsStats($input);
            $ratings = $this->getRatingsStats($input);
            return array_merge($referrals, $ratings);
        }
    }

    public function getReferralsStats($input) {

        $where = "unique_key.community_id = :community_id ";
        $parameters = ['community_id' => $input->community_id];
        if (isset($input->start_date) && isset($input->end_date)) {
            $where .= " and date(referrals.added) between :start_date and :end_date ";
            $parameters['start_date'] = $input->start_date;
            $parameters['end_date'] = $input->end_date;
        } else if (isset($input->start_date) && !isset($input->end_date)) {
            $where .= " and date(referrals.added) = :start_date ";
            $parameters['start_date'] = $input->start_date;
        }

        $s_query = "
            select 
                referrals.id,
                referrals.unique_key_id,
                referrals.user_key_id,
                user_key.user_id,
                unique_key.community_id,
                unique_key.content_id,
                'referrals' as action,
                referrals.added
            from referrals
                inner join user_key on user_key.id = referrals.user_key_id
                inner join unique_key on unique_key.id = referrals.unique_key_id
            where 
                referrals.deleted = '' and 
                $where
        ";

        $this->db->prepareStatement($s_query);
        $this->db->execute($parameters);
        return $this->db->fetchAll();
    }

    public function getRatingsStats($input) {

        $where = "unique_key.community_id = :community_id ";
        $parameters = ['community_id' => $input->community_id];
        if (isset($input->start_date) && isset($input->end_date)) {
            $where .= " and date(comments.added) between :start_date and :end_date ";
            $parameters['start_date'] = $input->start_date;
            $parameters['end_date'] = $input->end_date;
        } else if (isset($input->start_date) && !isset($input->end_date)) {
            $where .= " and date(comments.added) = :start_date ";
            $parameters['start_date'] = $input->start_date;
        }

        $s_query = "
            select 
                comments.id,
                comments.unique_key_id,
                comments.user_key_id,
                user_key.user_id,
                unique_key.community_id,
                unique_key.content_id,
                'ratings' as action,
                comments.added
            from comments
                inner join user_key on user_key.id = comments.user_key_id
                inner join unique_key on unique_key.id = comments.unique_key_id
            where 
                comments.deleted = '' and 
                $where
        ";
        $this->db->prepareStatement($s_query);
        $this->db->execute($parameters);
        return $this->db->fetchAll();
    }

}