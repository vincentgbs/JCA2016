<?php
// require_once FILE . 'framework/model/model.php';
require_once 'cmsModel.php';

class jcaModel extends cmsModel {

    public function createEvent($event)
    {
        $q = "INSERT INTO `jca_ls_events` (`event_image`, `event_date`, `event_title`, `event_body`)
            VALUES ({$this->wrap($event->event_image)},
                {$this->wrap($event->event_date)},
                {$this->wrap($event->event_title)},
                {$this->wrap($event->event_body)});";
        return $this->execute($q);
    }

    public function readEvents($current=true, $limit=false)
    {
        $q = 'SELECT *,
            DATE_FORMAT(`event_date`, \'%M %d, %Y %h:%i %p\') as `full_date_time`,
            DATE_FORMAT(`event_date`, \'%M %d, %Y\') as `full_date`,
            DATE_FORMAT(`event_date`, \'%b %d, %Y %h:%i %p\') as `abbreviation_date_time`,
            DATE_FORMAT(`event_date`, \'%b %d, %Y\') as `abbreviation_date`
            FROM jca_ls_events';
        if ($current) {
            $q .= " WHERE `event_date` >= now() - INTERVAL 1 DAY";
        }
        if ($limit) {
            $q .= " LIMIT {$limit}";
        }
        return $this->select($q);
    }

    public function updateEvent($update, $event)
    {
        $q = "UPDATE `jca_ls_events` SET ";
        foreach ($update as $key => $value) {
            $q .= " `$key` = {$this->wrap($value)},";
        }
        $q = substr($q, 0, -1) . " WHERE `event_id`={$event->event_id};";
        return $this->execute($q);
    }

    public function deleteEvent($event)
    {
        $q = "DELETE FROM `jca_ls_events` WHERE";
        foreach ($event as $key => $value) {
            $q .= " `$key` = {$this->wrap($value)} AND";
        }
        $q = substr($q, 0, -4) . ' LIMIT 1;';
        return $this->execute($q);
    }

    public function createBanner($banner)
    {
        //
    }

    public function readBanner($banner)
    {
        //
    }

    public function updateBanner($update, $banner)
    {
        //
    }

    public function deleteBanner($banner)
    {
        //
    }

}
?>
