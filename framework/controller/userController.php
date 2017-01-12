<?php
require_once FILE . 'framework/controller/controller.php';

class userController extends controller {

    public function __construct()
    {
        parent::__construct();
        $this->getModel('user');
        $this->getView('user');
        $this->getSettings('user');
        $this->flashMessage();
    }

    public function check()
    {
        if (isset($_SESSION['USER'])) {
            if ($this->checkPermissions($_SESSION['USER'])) {
                return true;
            } else {
                $this->flashMessage('401 Authorization Error: You do not have access to this page', 'user/home');
            }
        } // else return NULL
    }

    public function home()
    {
        if ($this->check()) {
            if (isset($_POST['function'])) {
                // update user
            }
            $this->userView->home();
        } else {
            $this->redirect('user/login', false, URL);
        }
    }

    public function register()
    {
        if (isset($_POST['username'], $_POST['email'], $_POST['password'])) {
            if ($this->csrfCheck()) {
                $username = $this->post('username', 'a', 99);
                $email = $this->post('email', 'e', 255);
                $password = $this->post('password', 'a', 64);
                $user = new user($this->userModel);
                return $user->registerUser($username, $email, $password);
            } // csrfCheck
            echo ($this->csrf_message);
        } else if (isset($_POST['username_search'])) {
            if ($this->userModel->checkUsername($this->post('username_search', 'a', 99))) {
                echo ('Username is already taken.'); return;
            } else {
                echo ('Username is still available.'); return;
            }
        }  else if (isset($_POST['email_search'])) {
            if ($this->userModel->checkEmail($this->post('email_search', 'e', 255))) {
                echo ('Email is already taken.'); return;
            } else {
                echo ('Email is still available.'); return;
            }
        } else if (isset($_GET['activation'])) {
            $user = new user($this->userModel);
            if ($user->checkUserExists(['activation' => $this->get('activation', 'a', 64)])) {
                $this->userModel->activateUser($user, 1);
                $this->userModel->createPermissions($user, [DEFAULTGROUP]);
                $this->redirect('user/login');
            }
        }
        $this->userView->register();
    }

    public function login()
    {
        if (isset($_POST['username'], $_POST['password'])) {
            if ($this->csrfCheck()) {
                $email = $this->post('username', 'e', 255);
                $user = new user($this->userModel);
                if (!$user->checkEmailOrUsername($email, ['active'=>1])) {
                    echo ('Please make sure that your username or email is correct
                    and your account is active.');
                    return $this->userView->login();
                }
                $brute = $this->userModel->readBrute($user);
                if (count($brute) > MAXATTEMPTS) {
                    echo ("You have attempted too many login attempts ({$brute}). Please try again later.");
                    return $this->userView->login();
                }
                $password = $this->post('password', 'a', 64);
                $user->ip();
                if ($user->checkPassword($password)) {
                    $this->setSession($user);
                    $this->userModel->createLogin($user, 1);
                    return $this->redirect('user/home', true);
                } else {
                    $this->userModel->createLogin($user);
                    echo ('The username or password you entered was invalid.<br>
                    Repeated failed attempts may result in your account being locked out.');
                    return $this->userView->login();
                }
            } // csrfCheck
            echo ($this->csrf_message);
        }
        $this->userView->login();
    }

    public function logout()
    {
        $this->endSession();
        $this->redirect('user/home');
    }

    public function reset()
    {
        $user = new user($this->userModel);
        if (isset($_POST['password'], $_POST['reset_code'])) {
            if ($this->csrfCheck()) {
                if (!$user->checkUserExists(['reset_code'=> $this->post('reset_code', 'a', 64)], 'readReset')) {
                    echo ('Your reset code has expired.'); return;
                }
                $reset = (object)['user_id' => $user->user_id];
                if ($user->resetPassword($reset, $this->post('password', 'a', 64))) {
                    return $this->userModel->deleteReset($reset);
                }
            } // csrfCheck
            echo ($this->csrf_message); return;
        } else if (isset($_POST['username'])) {
            if ($this->csrfCheck()) {
                $email = $this->post('username', 'e', 255);
                if (!$user->checkEmailOrUsername($email, ['active' => 1])) {
                    echo ('Username or email not found'); return;
                }
                return $user->createResetCode($user);
            } // csrfCheck
            echo ($this->csrf_message); return;
        }
        if (isset($_GET['reset_code'])) {
            if (!$user->checkUserExists(['reset_code'=> $this->get('reset_code', 'a', 64)], 'readReset')) {
                echo ('Your reset code has expired.');
                return $this->userView->reset(NULL);
            }
            return $this->userView->reset($user);
        }
        $this->userView->request();
    }

    public function deactivate()
    {
        if ($this->check()) {
            if (isset($_POST['password'])) {
                if ($this->csrfCheck()) {
                    if ($_SESSION['USER']->checkPassword($this->post('password', 'a', 64))) {
                        $this->userModel->deactivateUser($_SESSION['USER']);
                        $this->endSession();
                        return $this->redirect('user/register');
                    } else {
                        $this->flashMessage('Incorrect password.', 'user/deactivate');
                    }
                } // csrfCheck
                $this->flashMessage($this->csrf_message, 'user/deactivate');
            }
            $this->userView->deactivate();
        } else {
            $this->redirect('user/login', false, URL);
        }
    }

    private function setSession($user)
    {
        $_SESSION['USER'] = $user;
        $this->refreshSession();
    }

    private function getPermissions($user)
    {
        $this->permissions = [];
        $permissions = $this->userModel->readPermissions($user);
        if (is_array($permissions)) {
            foreach ($permissions as $permission) {
                $this->permissions[] = $permission->function;
            }
        }
    }

    private function checkPermissions($user)
    {
        if (isset($this->permissions) ? NULL : $this->getPermissions($user));
        $universal = explode('/', URL)[0] . '/*';
        if (in_array($universal, $this->permissions)) {
            return true;
        } else if (in_array(URL, $this->permissions)) {
            return true;
        } else {
            $this->userModel->createAccess($user);
            return false;
        }
    }

}

class user {

    public function __construct($model)
    {
        $this->userModel = $model;
    }

    public function registerUser($username, $email, $password, $activate=true)
    {
        $this->user_id = NULL;
        $this->username = $username;
        if (strlen($this->username) < 3) {
            exit($username . ' is not a valid username.');
        }
        if ($this->userModel->checkUsername($this->username)) {
            exit($username . ' is already taken.');
        }
        $this->email = $email;
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            exit($email . ' is not a valid email address.');
        } else if ($this->userModel->checkEmail($this->email)) {
            exit($email . ' is already registered.');
        }
        $this->createActivationCode();
        $this->hashPassword($password);
        if ($this->userModel->createUser($this) && $activate) {
            return $this->activationEmail($this);
        }
    }

    public function createActivationCode()
    {
        do {$this->activation = hash('sha1', mt_rand());
        } while ($this->userModel->checkActivationCode($this->activation));
    }

    public function createResetCode()
    {
        do {$this->reset_code = bin2hex(random_bytes(32));
        } while ($this->userModel->checkResetCode($this->reset_code));
        $this->userModel->createReset($this);
        return $this->resetEmail($this);
    }

    public function resetPassword($reset, $password)
    {
        $this->hashPassword($password);
        $password = new stdClass();
        $password->password = $this->password;
        $password->salt = $this->salt;
        if ($this->userModel->updateUser($reset, $password)) {
            $this->userModel->deleteBrute($this);
            echo ('Your password has been reset.'); return true;
        } else {
            exit('Database error while resetting password');
        }
    }

    public function checkEmailOrUsername($check, $array=[])
    {
        if (stristr($check, '@')) {
            $array['email'] = $check;
        }
        else {
            $array['username'] = $check;
        }
        return $this->checkUserExists($array);
    }

    public function checkUserExists($array, $query='readUser')
    {
        $user = $this->userModel->$query( (object)$array );
        if (isset($user[0], $user[0]->user_id)) {
            foreach ($user[0] as $key => $value) {
                $this->$key = $value;
            }
            return true;
        } // else return NULL
    }

    public function hashPassword($password, $salt=NULL)
    {
        if (ENCRYPTION === 'BCRYPT') {
            $this->password = password_hash($password, PASSWORD_BCRYPT, ['cost'=>12]);
            return $this->salt = 'BCRYPT_COMBINES_SALT_WITH_PASSWORD';
        } else { // (ENCRYPTION === 'PBKDF2')
            if ($salt === NULL) { $salt = bin2hex(random_bytes(32)); }
            foreach (range(1, HASHITERATIONS) as $i) {
                $password = hash('sha256', $salt . $password);
            }
            $this->password = $password;
            return $this->salt = $salt;
        }
    }

    public function checkPassword($password)
    {
        if (ENCRYPTION === 'BCRYPT') {
            return password_verify($password, $this->password);
        } else { // (ENCRYPTION === 'PBKDF2')
            $check = new user(NULL);
            $check->hashPassword($password, $this->salt);
            return ($check->password === $this->password);
        }
    }

    public function ip()
    {
        $ip['REMOTE_ADDR'] = (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : NULL);
        (isset($_SERVER['HTTP_CLIENT_IP'])?$ip['HTTP_CLIENT_IP'] = $_SERVER['HTTP_CLIENT_IP']:NULL);
        (isset($_SERVER['HTTP_X_FORWARDED_FOR'])?$ip['HTTP_X_FORWARDED_FOR'] = $_SERVER['HTTP_X_FORWARDED_FOR']:NULL);
        (isset($_SERVER['HTTP_X_FORWARDED'])?$ip['HTTP_X_FORWARDED'] = $_SERVER['HTTP_X_FORWARDED']:NULL);
        (isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])?$ip['HTTP_X_CLUSTER_CLIENT_IP'] = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP']:NULL);
        (isset($_SERVER['HTTP_FORWARDED_FOR'])?$ip['HTTP_FORWARDED_FOR'] = $_SERVER['HTTP_FORWARDED_FOR']:NULL);
        (isset($_SERVER['HTTP_FORWARDED'])?$ip['HTTP_FORWARDED'] = $_SERVER['HTTP_FORWARDED']:NULL);
        $this->ip = serialize($ip);
    }

    private function activationEmail($user)
    {
        $message = 'Please confirm your email with this link:
        <a href="{{{$url}}}/user/register?activation={{{$activation}}}.">Activate</a>';
        $message = str_replace('{{{$url}}}', DOMAIN, $message);
        $message = str_replace('{{{$activation}}}', $user->activation, $message);
        if (DEBUG == 'ON') {
            echo $message; return;
        } else if (EMAIL == 'PHP') {
            mail($user->email, 'Activate your account', $message);
            echo 'Please check your email for confirmation'; return;
        } else {
            exit('System Admin: No email method selected');
        }
    }

    private function resetEmail($user)
    {
        $message = 'You can reset your password by going to:
        <a href="/user/reset?reset_code={{{$reset}}}">Reset Password</a>';
        $message = str_replace('{{{$reset}}}', $user->reset_code, $message);
        if (DEBUG == 'ON') {
            echo $message; return;
        } else if (EMAIL == 'PHP') {
            mail($user->email, 'Reset your password', $message);
            echo 'Please check your email for the reset link'; return;
        } else {
            exit('System Admin: No email method selected');
        }
    }

}
?>
<?php
// public function googleLogin()
// {
//     if (isset($_POST['email'], $_POST['id_token'], $_POST['name'])) {
//         $user['email'] = $this->post('email');
//         $google_token = $this->post('id_token');
//         $name = $this->post('name');
//         $url = $this->settings['google_verify_url'] . $google_token;
//         $output = json_decode($this->simpleCurl($url));
//         if ($output === null) { exit('Google Authentification Error'); }
//         if ($output->aud == $this->settings['google_client_id']
//                 && $output->email == $user['email'] && $output->name == $name) {
//             if ($output->exp <= 0) {
//                 exit('Google Authentification expired');
//             }
//             $check = $this->getUser('all', $user);
//             if (isset($check['user_id'])) {
//                 $this->setSession($check);
//                 echo ('Login successful.');
//                 if (isset($_SESSION['last_page'])) { echo '<br><a href="?url='.$_SESSION['last_page'].'">Continue</a>'; }
//                 return;
//             } else {
//                 $user['password'] = 'registered_with_google';
//                 $user['salt'] = 'registered_with_google';
//                 $user['username'] = preg_replace("/(@gmail.com)/", '', $user['email']);
//                 $user['activation'] = hash('sha1', mt_rand());
//                 if ($this->userModel->addUser($user)) {
//                     $user = $this->getUser('all', $user);
//                     $this->userModel->addPermission($user['user_id'], [1]);
//                     $this->setSession($user);
//                     echo ('Google Login successful');
//                     if (isset($_SESSION['last_page'])) { echo '<br><a href="?url='.$_SESSION['last_page'].'">Continue</a>'; }
//                     return;
//                 } else {
//                     exit('500 Internal Server Error: Database error during registration');
//                 }
//             }
//         } else {
//             exit('Google Authentification Error');
//         }
//     }
//     $data['client_id'] = $this->settings['google_client_id'];
//     $this->userView->googleLogin($data);
// }
?>
