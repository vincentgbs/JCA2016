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

    public function readEvents($current=false, $order=false, $limit=false)
    {
        $q = 'SELECT *,
            DATE_FORMAT(`event_date`, \'%M %d, %Y %l:%i %p\') as `full_date_time`,
            DATE_FORMAT(`event_date`, \'%M %d, %Y\') as `full_date`,
            DATE_FORMAT(`event_date`, \'%b %d, %Y %l:%i %p\') as `abbreviation_date_time`,
            DATE_FORMAT(`event_date`, \'%b %d, %Y\') as `abbreviation_date`
            FROM jca_ls_events';
        if ($current) {
            $q .= " WHERE (`event_date` >= NOW() - INTERVAL 1 DAY OR `event_date` IS NULL)  ";
        }
        if ($order) { $q .= " ORDER BY {$order}"; }
        if ($limit) { $q .= " LIMIT {$limit};"; }
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
        $q = "INSERT INTO `jca_ls_banner`
            (`banner_id`, `banner_title`, `banner_body`, `commencement`, `expiration`)
            VALUES (1, {$this->wrap($banner->banner_title)},
            {$this->wrap($banner->banner_body)},
            {$this->wrap($banner->commencement)},
            {$this->wrap($banner->expiration)})
            ON DUPLICATE KEY UPDATE
            `banner_title`={$this->wrap($banner->banner_title)},
            `banner_body`={$this->wrap($banner->banner_body)},
            `commencement`={$this->wrap($banner->commencement)},
            `expiration`={$this->wrap($banner->expiration)};";
        return $this->execute($q);
    }

    public function readBanner($current=false)
    {
        $q = 'SELECT * FROM `jca_ls_banner`';
        if ($current) {
            $q .= ' WHERE `commencement` <= NOW() AND `expiration` >= NOW();';
        }
        return $this->select($q);
    }

    public function deleteBanner()
    {
        return $this->execute('DELETE FROM `jca_ls_banner` WHERE `banner_id`=1;');
    }

    public function createSermon($sermon)
    {
        $q = "INSERT INTO `jca_ls_sermons` (`sermon_speaker`, `sermon_date`, `sermon_title`, `sermon_event`, `sermon_passage`, `sermon_url`, `sermon_series`)
        VALUES ({$this->wrap($sermon->sermon_speaker)}, {$this->wrap($sermon->sermon_date)}, {$this->wrap($sermon->sermon_title)}, {$this->wrap($sermon->sermon_event)}, {$this->wrap($sermon->sermon_passage)}, {$this->wrap($sermon->sermon_url)}, {$this->wrap($sermon->sermon_series)});";
        return $this->execute($q);
    }

    public function readSermons($order='`sermon_date` DESC', $limit=false)
    {
        $q = 'SELECT *, DATE_FORMAT(`sermon_date`, \'%b %d, %Y\') as `abbreviation_date` FROM `jca_ls_sermons`';
        if ($order) {
            $q .= " ORDER BY {$order}";
        }
        if ($limit) {
            $q .= " LIMIT {$limit};";
        }
        return $this->select($q);
    }

    public function updateSermon($update, $sermon)
    {
        $q = 'UPDATE `jca_ls_sermons` SET ';
        foreach ($update as $key => $value) {
            $q .= " `$key` = {$this->wrap($value)},";
        }
        $q = substr($q, 0, -1) . " WHERE `sermon_id`={$sermon->sermon_id};";
        return $this->execute($q);
    }

    public function deleteSermon($sermon)
    {
        $q = 'DELETE FROM `jca_ls_sermons` WHERE';
        foreach ($sermon as $key => $value) {
            $q .= " `$key` = {$this->wrap($value)} AND";
        }
        $q = substr($q, 0, -4) . ';';
        return $this->execute($q);
    }

    // public function createForm() { }
    // public function deleteForm() { }

    public function readForms($form=false)
    {
        $q = 'SELECT * FROM jca_ls_forms';
        if ($form) {
            $q .= ' WHERE';
            foreach ($form as $key => $value) {
                $q .= " `{$key}`={$this->wrap($value)} AND";
            }
            $q = substr($q, 0, -4) . ';';
        }
        return $this->select($q);
    }

    public function updateForm($update, $form)
    {
        $q = "UPDATE `jca_ls_forms` SET ";
        foreach ($update as $key => $value) {
            $q .= " `$key` = {$this->wrap($value)},";
        }
        $q = substr($q, 0, -1) . " WHERE `form_id`={$form->form_id};";
        return $this->execute($q);
    }

    public function createResponse($response)
    {
        $q = 'INSERT INTO `jca_ls_responses` (';
        $keys = ''; $values = '';
        foreach ($response as $key => $value) {
            $keys .= "`$key`, ";
            $values .= "{$this->wrap($value)}, ";
        }
        $q .= substr($keys, 0, -2) . ') VALUES (' . substr($values, 0, -2) . ');';
        return $this->execute($q);
    }

    public function readResponses($response=false)
    {
        $q = 'SELECT * FROM `jca_ls_responses`';
        if ($response) {
            $q .= ' WHERE';
            foreach ($response as $key => $value) {
                $q .= " `$key` = {$this->wrap($value)} AND";
            }
            $q = substr($q, 0, -4) . ';';
        }
        return $this->select($q);
    }

    public function deleteResponses($response)
    {
        $q = "DELETE FROM `jca_ls_responses` WHERE `response_id`={$response->response_id};";
        return $this->execute($q);
    }

}
?>
