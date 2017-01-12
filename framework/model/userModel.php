<?php
require_once FILE . 'framework/model/model.php';

class userModel extends model {

    public function createUser($user)
    {
        $q = "INSERT INTO `user_ls_users`
        (`user_id`, `username`, `email`, `password`, `salt`, `activation`, `active`, `registration_date`)
        VALUES ({$this->wrap($user->user_id)},
            {$this->wrap($user->username)},
            {$this->wrap($user->email)},
            {$this->wrap($user->password)},
            {$this->wrap($user->salt)},
            {$this->wrap($user->activation)},
            0,
            NULL);";
        if ($this->execute($q)) { return true; }
        else { exit('Database error creating new user'); }
    }

    public function readUser($user)
    {
        $q = 'SELECT * FROM `user_ls_users` WHERE';
        foreach ($user as $key => $value) {
            $q .= " `{$key}` = {$this->wrap($value)} AND";
        }
        $q = substr($q, 0, -4) . ';';
        return $this->select($q);
    }

    public function updateUser($user, $update)
    {
        $q = 'UPDATE `user_ls_users` SET';
        foreach ($update as $key => $value) {
            $q .= " `{$key}`={$this->wrap($value)},";
        }
        $q = substr($q, 0, -1) . ' WHERE';
        foreach ($user as $key => $value) {
            $q .= " `{$key}`={$this->wrap($value)} AND";
        }
        $q = substr($q, 0, -4) . ' LIMIT 1;';
        return $this->execute($q);
    }

    // public function deleteUser($user)
    // {
    //     $q = 'DELETE FROM `user_ls_users` WHERE';
    //     foreach ($user as $key => $value) {
    //         $q .= " `{$key}` = {$this->wrap($value)} AND";
    //     }
    //     $q = substr($q, 0, -4) . ';';
    //     return $this->select($q);
    // }

    public function createLogin($user, $success=0)
    {
        $q = "INSERT INTO `user_ls_login` (`user_id`, `ip_address`, `success`)
            VALUES ({$user->user_id}, '{$user->ip}', {$success});";
        if ($this->execute($q)) { return true; }
        else { exit('Database error'); }
    }

    public function readBrute($user, $interval='1 HOUR')
    {
        $q = "SELECT * FROM `user_ls_login`
            WHERE `user_id`={$user->user_id}
            AND `success`=0
            AND `timestamp` >= now() - INTERVAL {$interval};";
        return $this->select($q);
    }

    public function deleteBrute($user)
    {
        $q = "DELETE FROM `user_ls_login`
            WHERE `user_id`={$user->user_id}
            AND `success`=0 LIMIT 1;";
        $this->execute($q);
    }

    public function createReset($user)
    {
        $q = "INSERT INTO `user_ls_reset` (`user_id`, `reset_code`)
            VALUES ({$user->user_id}, {$this->wrap($user->reset_code)})
             ON DUPLICATE KEY UPDATE `reset_code`={$this->wrap($user->reset_code)};";
         if ($this->execute($q)) { return true; }
         else { exit('Database error'); }
    }

    public function readReset($user, $interval='30 MINUTE')
    {
        $q = "SELECT * FROM `user_ls_reset`
            WHERE `reset_code`={$this->wrap($user->reset_code)}
            AND `timestamp` >= now() - INTERVAL {$interval};";
        return $this->select($q);
    }

    public function deleteReset($user)
    {
        $q = 'DELETE FROM `user_ls_reset` WHERE ';
        foreach ($user as $key => $value) {
            $q .= " `{$key}`={$this->wrap($value)} AND";
        }
        $q = substr($q, 0, -4) . ';';
        return $this->execute($q);
    }

    public function createPermissions($user, $groups)
    {
        $q = 'INSERT INTO `user_rel_groups` (`user_id`, `group_id`)
            VALUES';
        foreach ($groups as $group) {
            $q .= " ({$user->user_id}, {$group}),";
        }
        $q = substr($q, 0, -1) . ';';
        return $this->execute($q);
    }

    public function readPermissions($user)
    {
        $q = 'SELECT `permission_id`, `group_id`, `user_id`, `function`
            FROM `user_view_permissions` WHERE `user_id`=' . $user->user_id;
        return $this->select($q);
    }

    public function deletePermission($user, $group)
    {
        $q = "DELETE FROM `user_rel_groups`
            WHERE `user_id`={$user->user_id}
            AND `group_id`={$group};";
        return $this->execute($q);
    }



    public function checkUsername($username)
    {
        $user = $this->readUser((object)['username'=>$username]);
        if (isset($user[0], $user[0]->user_id)) {
            return true;
        }
    }

    public function checkEmail($email)
    {
        $user = $this->readUser((object)['email'=>$email]);
        if (isset($user[0], $user[0]->user_id)) {
            return true;
        }
    }

    public function checkActivationCode($code)
    {
        $user = $this->readUser((object)['activation'=>$code]);
        if (isset($user[0], $user[0]->user_id)) {
            return true;
        }
    }

    public function checkResetCode($code)
    {
        $user = $this->readReset((object)['reset_code'=>$code]);
        if (isset($user[0], $user[0]->user_id)) {
            return true;
        }
    }

    public function activateUser($user, $active)
    {
        $user = (object)['user_id' => $user->user_id];
        $update = (object)['active' => $active];
        $this->updateUser($user, $update);
    }

    public function deactivateUser($user)
    {
        $this->activateUser($user, 0);
    }

}
?>
