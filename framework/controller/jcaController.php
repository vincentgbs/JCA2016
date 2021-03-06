<?php
require_once FILE . 'framework/controller/controller.php';

class jcaController extends controller {

    public function __construct()
    {
        parent::__construct();
        $this->getModel('jca');
        $this->getView('jca');
    }

    public function home()
    {
        if (isset($_GET['page'])) {
            $page = (object)['page_name' => strtolower($this->get('page', 'a', 99))];
        } else {
            $page = (object)['page_name' => JCADEFAULTPAGE];
        }
        $page = $this->jcaModel->readPage($page);
        $this->bannerCheck();
        $this->jcaView->simple($page);
    }

    // special pages
    public function index()
    {
        $templates['top'] = ['headtop', 'headindex', 'headbot', 'indextop'];
        $this->bannerCheck();
        $templates['loop'] = 'announcement';
        $templates['bottom'] = ['indexbot', 'footer'];
        $events = $this->jcaModel->readEvents(true, '-event_order DESC', 2);
        return $this->jcaView->oneloop($templates, $events);
    }

    // special pages
    public function events()
    {
        $templates['top'] = ['headtop', 'headevents', 'headbot', 'eventstop'];
        $this->bannerCheck();
        $templates['loop'] = 'event';
        $templates['bottom'] = ['eventsbot', 'footer'];
        $events = $this->jcaModel->readEvents(true, '-event_order DESC');
        return $this->jcaView->oneloop($templates, $events);
    }

    // special pages
    public function sermons()
    {
        $templates['top'] = ['headtop', 'headsermons', 'headbot', 'sermonstop'];
        $this->bannerCheck();
        $templates['loop'] = 'sermon';
        $templates['bottom'] = ['sermonsbot', 'footer'];
        $sermons = $this->jcaModel->readSermons();
        return $this->jcaView->oneloop($templates, $sermons);
    }

    public function forms()
    {
        if (isset($_POST['form_name'])) {
            $form = ['form_name'=>$this->post('form_name', 'a', 99)];
            $form = $this->jcaModel->readForms($form);
            if (isset($form[0])) { $form = $form[0]; }
            else { exit('Invalid form submission.'); }

            if (isset($form->google_spreadsheet_id, $form->google_spreadsheet_range)) {
                $response = new stdClass();
                foreach ($_POST as $key => $value) {
                    switch ($key) {
                        case 'email':
                            $response->email = $this->post('email', 'e', 255);
                            $google[] = $response->email;
                        break;
                        case 'name':
                            $response->name = $this->post('name', 'w', 99);
                            $google[] = $response->name;
                        break;
                        case 'group':
                            $response->group = $this->post('group', 'a', 99);
                            $google[] = $response->group;
                        break;
                        case 'message':
                            $response->message = $this->post('message', 'w', 999);
                            $google[] = $response->message;
                        break;
                        case 'children':
                            $response->children = $this->post('children', 'w', 255);
                            $google[] = $response->children;
                        break;
                        default: NULL;
                    }
                }
                if (isset($form->email_notification) && $form->email_notification != '') {
                    $message = 'A new form was submitted. Please visit:
                        https://docs.google.com/spreadsheets/d/' . $form->google_spreadsheet_id
                        . ' for more information.';
                    if (DEBUG == 'ON') {
                        echo ($message);
                    } else if (EMAIL == 'PHP') {
                        mail($form->email_notification, 'New form submission', $message);
                    }
                }
                if (isset($google)) {
                    $google[] = date('Y-m-d H:i:s'); // timestamp
                    require_once FILE . 'framework/libraries/googleDocsApi.php';
                    $googleDocsApi = new googleDocsApi();
                    $googleDocsApi->writeToGoogleSpreadsheet($form->google_spreadsheet_id, $form->google_spreadsheet_range, [$google]);
                }
                if (isset($response)) {
                    $response->form_id = $form->form_id;
                    $this->jcaModel->createResponse($response);
                }
                echo ('Form submitted.');
            }
        }
    }

    public function edit()
    {
        $this->getController('user');
        if (!$this->userController->check()) {
            $this->flashMessage('Please log in to access this page.');
            $this->redirect('user/login', false, URL);
        }
        if (isset($_POST['edit'])) {
            $form = $this->post('edit', 'a', 32);
            if (in_array($form, ['banner', 'events', 'sermons', 'forms'])) {
                $function = 'read' . ucfirst($form);
                $data = $this->jcaModel->$function();
                $this->jcaView->loadTemplate('jca/edit/' . $form, $data);
                return $this->jcaView->display(false);
            } else {
                exit('Invalid edit function.');
            }
        } else if (isset($_POST['function'])) {
            require_once FILE . 'framework/libraries/simpleChunking.php';
            switch ($this->post('function', 'a', 32)) {
                case 'banner':
                    if (isset($_POST['delete'])) {
                        return $this->jcaModel->deleteBanner();
                    }
                    $banner = new stdClass();
                    $banner->banner_title = $this->post('banner_title', NULL, 99);
                    $banner->banner_body = $this->post('banner_body', NULL, 999);
                    $banner->commencement = $this->post('commencement', 's', 32, '-');
                    $banner->expiration = $this->post('expiration', 's', 32, '-');
                    if ($this->jcaModel->createBanner($banner)) {
                        echo ('title: ' . $banner->banner_title .
                            '<br>body: ' . $banner->banner_body); return;
                    } else {
                        exit('Error updating banner.');
                    }
                break;
                case 'events':
                    if (isset($_POST['delete'])) {
                        $event = (object)['event_id' => $this->post('event_id', 'i')];
                        if ($this->jcaModel->deleteEvent($event)) {
                            echo ('Event deleted.'); return;
                        } else {
                            exit('Error deleting event.');
                        }
                    } else if (isset($_POST['update'])) {
                        $event = (object)['event_id' => $this->post('event_id', 'i')];
                        $update = $this->eventFromPost();
                        if ($this->jcaModel->updateEvent($update, $event)) {
                            echo ('Event updated.'); return;
                        } else {
                            exit('Error updating event.');
                        }
                    }
                    $event = $this->eventFromPost();
                    $filetype = $this->post('filetype', 'a', 16);
                    if (in_array($filetype, ['imagepng', 'imagejpeg'])) {
                        $event->event_image = base64_encode(file_get_contents($_FILES['event_image']['tmp_name']));
                        if ($this->jcaModel->createEvent($event)) {
                            echo ('Event created.'); return;
                        } else {
                            exit('Error creating event.');
                        }
                    } else {
                        exit('Invalid image type.');
                    }
                break;
                case 'sermons':
                    if (isset($_POST['delete'])) {
                        $sermon = (object)['sermon_id' => $this->post('sermon_id', 'i')];
                        if ($this->jcaModel->deleteSermon($sermon)) {
                            echo ('Sermon deleted.'); return;
                        } else {
                            exit('Error deleting sermon.');
                        }
                    } else if (isset($_POST['update'])) {
                        $sermon = (object)['sermon_id' => $this->post('sermon_id', 'i')];
                        $update = $this->sermonFromPost();
                        if ($this->jcaModel->updateSermon($update, $sermon)) {
                            echo ('Sermon updated.'); return;
                        } else {
                            exit('Error updating sermon.');
                        }
                    }
                    $sermon = $this->sermonFromPost();
                    if ($this->jcaModel->createSermon($sermon)) {
                        echo ('Sermon created.'); return;
                    } else {
                        exit('Error creating sermon.');
                    }
                break;
                case 'forms':
                    if (isset($_POST['delete'])) {
                        exit('Currently unable to delete forms.');
                    } else if (isset($_POST['update'])) {
                        $form = (object)['form_id' => $this->post('form_id', 'i')];
                        $update = new stdClass();
                        $emails = explode(',', $_POST['email_notification']);
                        foreach ($emails as &$email) { // & changes $email variable
                            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
                        }
                        $update->email_notification = implode(',', $emails);
                        $update->google_spreadsheet_id = $this->post('google_spreadsheet_id', 's', 255, '_');
                        $update->google_spreadsheet_range = $this->post('google_spreadsheet_range', 's', 255, '!:');
                        if ($this->jcaModel->updateForm($update, $form)) {
                            echo ('Form updated.'); return;
                        } else {
                            exit('Error updating form.');
                        }
                    }
                    exit('Currently unable to create forms.');
                break;
                default: exit('Invalid edit function.');
            }
        } else if (isset($_GET['only'])) {
            $form = $this->get('only', 'a', 32);
            if (in_array($form, ['banner', 'events', 'sermons', 'forms'])) {
                $function = 'read' . ucfirst($form);
                $data = $this->jcaModel->$function();
                return $this->jcaView->limitedEdit($form, $data);
            } else {
                exit('Invalid function request.');
            }
        }
        return $this->jcaView->edit();
    }

    public function editSpecial()
    {
        $this->getController('user');
        if (!$this->userController->check()) {
            $this->flashMessage('Please log in to access this page.');
            $this->redirect('user/login', false, URL);
        }
        $this->getView('cms');
        $page = ['headtop', 'headindex', 'headbot', 'indextop',
            'announcement', 'indexbot', 'footer'];
        foreach ($page as $html) {
            $filename = FILE . 'html/cache/html/' . $html . '.html';
            $resource = (object) ['type' => 'html', 'name' => $html];
            if (is_file($filename)) {
                $resource->resource = file_get_contents($filename);
                $resource->resource = str_replace('<', '&lt;', $resource->resource);
                $resource->resource = str_replace('>', '&gt;', $resource->resource);
                $this->cmsView->loadTemplate('cms/edit/update', $resource);
            }
        }
        $this->cmsView->body .= '<style>.delete_resource_button{display:none;}</style>';
        $this->cmsView->loadTemplate('cms/edit/foot');
        return $this->cmsView->display();
    }

    private function eventFromPost()
    {
        $event = new stdClass();
        $event->event_date = $this->post('event_date', 's', 32, '-: ');
        if ($event->event_date == '') { $event->event_date = NULL; }
        $event->event_order = $this->post('event_order', 'i');
        if ($event->event_order == '') { $event->event_order = NULL; }
        $event->event_title = $this->post('event_title', NULL, 255);
        $event->event_body = $this->post('event_body', NULL, 2047);
        return $event;
    }

    private function sermonFromPost()
    {
        $sermon = new stdClass();
        $sermon->sermon_speaker = $this->post('sermon_speaker', NULL, 255);
        $sermon->sermon_date = $this->post('sermon_date', NULL, 255, '-: ');
        $sermon->sermon_title = $this->post('sermon_title', NULL, 255);
        $sermon->sermon_event = $this->post('sermon_event', NULL, 255);
        $sermon->sermon_passage = $this->post('sermon_passage', NULL, 255);
        $sermon->sermon_url = $this->post('sermon_url', 'r', 99);
        $sermon->sermon_series = $this->post('sermon_series', NULL, 255);
        return $sermon;
    }

    private function bannerCheck()
    {
        $banner = $this->jcaModel->readBanner(true);
        if (isset($banner[0], $banner[0]->banner_title, $banner[0]->banner_body)) {
            if (is_file(FILE . 'html/cache/html/emergencybanner.html')) {
                $html = file_get_contents(FILE . 'html/cache/html/emergencybanner.html');
                $html = htmlspecialchars_decode(str_replace("{{{@banner_title}}}", $banner[0]->banner_title, $html));
                $html = htmlspecialchars_decode(str_replace("{{{@banner_body}}}", $banner[0]->banner_body, $html));
            } else { // default banner display
                $html = "<p>{$banner[0]->banner_title}<br>{$banner[0]->banner_body}</p>";
            }
            $this->jcaView->banner = $html;
        } else {
            return false;
        }
    }

}
?>
