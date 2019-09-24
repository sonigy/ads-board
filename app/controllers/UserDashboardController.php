<?php

namespace controllers;

use Ubiquity\utils\http\USession;
use Ubiquity\controllers\Startup;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\base\UFileSystem;

use Ubiquity\contents\validation\ValidatorsManager;
use Ubiquity\log\Logger;

use Ubiquity\utils\http\UResponse;

use Ubiquity\orm\DAO;

use models\Ads;
use models\User;

/**
 * Controller UserDashboardController
 **/
class UserDashboardController extends ControllerBase
{

    public function index()
    { }

    /**
     * @route("/user-dashboard","methods"=>["get"])
     **/
    public function userDashboard()
    {

        $ads = DAO::getOne(User::class, USession::get('activeUser')->getId())->getAdss();

        $this->loadView('UserDashboardController/userDashboard.html', compact('ads'));
    }

    /**
     * @route("/add","methods"=>["get", "post"])
     **/
    public function addAdvert()
    {

        $vio = [];
        $success = [];
        $violations = [];

        $sessIdUser = USession::get('activeUser')->getId();

        if (URequest::isPost() && $pAdvertForm = URequest::getPost()) {

            $adv = new Ads();

            $adv->setTitle($pAdvertForm['title']);
            $adv->setBody($pAdvertForm['body']);

            $violations = ValidatorsManager::validate($adv);

            if (mb_strlen($pAdvertForm['title']) <= 12) {
                array_push($violations, 'title: Title must be longer than 12 characters');
            } elseif (sizeof($violations) === 0) {

                if ($_FILES['image']['size'] > 0) {
                    $t_dir = UFileSystem::cleanPathname('public/assets/img/');
                    $t_file = UFileSystem::cleanFilePathname($t_dir . basename($_FILES['image']['name']));
                    $iType = strtolower(pathinfo($t_file, PATHINFO_EXTENSION));
                    $iHashName = md5(pathinfo($_FILES['image']['name'], PATHINFO_FILENAME));

                    if ($_FILES['image']['size'] > 5000000) {
                        array_push($violations, 'File is too large');
                    }

                    if ($iType != 'webp' && $iType != 'jpg' && $iType != 'png' && $iType != 'jpeg') {
                        array_push($violations, 'Only .WEBP .JPG, .JPEG, .PNG extension files are allowed');
                    }

                    $iFileHashName = $t_dir . basename($iHashName) . '.' . $iType;

                    if (file_exists($iFileHashName)) {
                        array_push($violations, 'File already exists. Try to change name file');
                    } elseif (is_array($violations) && sizeof($violations) === 0) {

                        if (move_uploaded_file($_FILES['image']['tmp_name'], $iFileHashName)) {
                            $adv->setImageName($iHashName);
                            $adv->setImageType($iType);
                        } else {
                            array_push($violations, 'Failed to write file to server. Try again');
                        }
                    }
                }

                if (is_array($violations) && sizeof($violations) === 0) {

                    $user = DAO::uGetOne(User::class, USession::get('activeUser')->getId(), false);
                    $adv->setUser($user);
                    $adv->setTs(date('Y-m-d H:i:s'));

                    try {
                        if (DAO::save($adv)) {
                            array_push($success, 'The new Advert was added.');
                            Logger::info('Database', 'The new Advert was added to the database from user id: '.$sessIdUser);
                        }
                    } catch (\PDOException $e) {
                        UResponse::header('Messages', 'Internal Server Error', false, 500);
                        Logger::error('Database', 'Failed add a new Advert in the database for user id: '.$sessIdUser, $e->getMessage());
                    } catch (\Exception $e) {
                        UResponse::header('Messages', 'Internal Server Error', false, 500);
                        Logger::error('Caught Exception', $e->getMessage());
                    }
                }
            }

        }

        if (is_array($violations) && sizeof($violations) > 0) {
            $vio = explode('~]*', implode('~]*', $violations));
        }

        $this->loadView('UserDashboardController/addAdvert.html', compact('vio', 'success'));
    }

    /**
     * @route("/update/{id}", "requirements"=>["id"=>"\d+"], "methods"=>["get", "post"])
     **/
    public function updateAdvert($id)
    {

        $violations = [];
        $vio = [];
        $success = [];
        $advert = [];

        $sessIdUser = USession::get('activeUser')->getId();

        if (is_int(intval($id)) && $adv = DAO::getOne(Ads::class, $id)) {

            if (URequest::isPost() && $pAdvertForm = URequest::getPost()) {

                $adv->setTitle($pAdvertForm['title']);
                $adv->setBody($pAdvertForm['body']);

                $violations = ValidatorsManager::validate($adv);

                if (mb_strlen($pAdvertForm['title']) <= 12) {
                    array_push($violations, 'title: Title must be longer than 12 characters');

                } elseif (sizeof($violations) === 0) {
                    
                    if (file_exists($_FILES['image']['tmp_name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
                        if ($_FILES['image']['size'] > 0 && $_FILES['image']['size'] <= 5000000) {
                            $dbOldImageName = $adv->getImageName();
                            $dbOldImageType = $adv->getImageType();

                            $t_dir = UFileSystem::cleanPathname('public/assets/img/');
                            $t_file = UFileSystem::cleanFilePathname($t_dir . basename($_FILES['image']['name']));
                            $newImageType = strtolower(pathinfo($t_file, PATHINFO_EXTENSION));
                            $newImageName = md5(pathinfo($_FILES['image']['name'], PATHINFO_FILENAME));
                            $newImage = $t_dir . basename($newImageName) . '.' . $newImageType;

                            if ($newImageType != 'webp' && $newImageType != 'jpg' && $newImageType != 'png' && $newImageType != 'jpeg') {
                                array_push($violations, 'Only .WEBP .JPG, .JPEG, .PNG extension files are allowed');
                            }
                            
                            if (is_array($violations) && sizeof($violations) === 0) {
                                if (move_uploaded_file($_FILES['image']['tmp_name'], $newImage)) {
                                    UFileSystem::deleteFile($t_dir.$dbOldImageName.'.'.$dbOldImageType);
                                    $adv->setImageName($newImageName);
                                    $adv->setImageType($newImageType);
                                } else {
                                    array_push($violations, 'Failed to write file to server. Try again');
                                }
                            }
                            
                        } else {
                            array_push($violations, 'File is too large');
                        }
                    }
                    

                    if (is_array($violations) && sizeof($violations) === 0) {
                        $adv->setTs(date('Y-m-d H:i:s'));
                        try {
                            if (DAO::save($adv)) {
                                array_push($success, 'The Advert was updated.');
                                Logger::info('Database', 'The Advert was updated in the database for user id: '.$sessIdUser);
                                echo 'The Advert was updated.';
                            }
                        } catch (\PDOException $e) {
                            UResponse::header('Messages', 'Internal Server Error', false, 500);
                            Logger::error('Database', 'Failed update Advert in the database for user id: '.$sessIdUser, $e->getMessage());
                        } catch (\Exception $e) {
                            UResponse::header('Messages', 'Internal Server Error', false, 500);
                            Logger::error('Caught Exception', $e->getMessage());
                        }
                        
                    }
                }
            }

            $idUserAd = $adv->getUser()->getId();
            if ($idUserAd === $sessIdUser) {
                $advert = $adv;
                
                // echo '<pre>'.print_r($advert).'</pre>';

            } else {
                UResponse::header('Messages', 'Forbidden', false, 403);
                Logger::info('Forbidden', 'The request is not allowed id: ' . $id . ' for user id: ' . $sessIdUser);
                echo 'Forbidden. The request is not allowed.';
            }

        } else {
            UResponse::header('Messages', 'Bad Request', false, 400);
            Logger::info('Bad request', 'The request id: ' . $id . ' for user id: ' . $sessIdUser);
            echo 'Bad Request';
        }
        
        if (is_array($violations) && sizeof($violations) > 0) {
            $vio = explode('~]*', implode('~]*', $violations));
        }

        $this->loadView('UserDashboardController/updateAdvert.html', compact('advert', 'vio', 'success'));
    }

    /**
     * @route("/remove/{id}","requirements"=>["id"=>"\d+"], "methods"=>["get"])
     **/
    public function removeAdvert($id)
    {

        if (is_int(intval($id)) && $adv = DAO::getOne(Ads::class, $id)) {
            $t_dir = UFileSystem::cleanPathname('public/assets/img/');
            $sessIdUser = USession::get('activeUser')->getId();

            try {
                // $adv = DAO::getOne(Ads::class, $id);

                $imageHashName = $adv->getImageName();
                $imageType = $adv->getImageType();
                $idUserAd = $adv->getUser()->getId();

                if ($idUserAd === $sessIdUser) {
                    if (DAO::delete(Ads::class, $id)) {
                        UFileSystem::deleteFile($t_dir.$imageHashName.'.'.$imageType);

                        UResponse::header('Messages', 'Gone', false, 410);
                        Logger::info('Database', 'Remove advert id: '.$id.' for user id: '.$sessIdUser.' from database');
                        echo 'Advert id: '.$id.' deleted from database';
                    }
                } else {
                    UResponse::header('Messages', 'Forbidden', false, 403);
                    Logger::info('Database', 'Remove advert id: '.$id.' for user id: '.$sessIdUser.' Not allowed.');
                    echo 'Forbidden. The request is not allowed.';
                }
            } catch (\PDOException $e) {
                UResponse::header('Messages', 'Internal Server Error', false, 500);
                Logger::error('Database', 'Failed to remove Advert id: '.$id.' for user id: '.$sessIdUser, $e->getMessage(), 'Delete');

            } catch (\Exception $e) {
                UResponse::header('Messages', 'Internal Server Error', false, 500);
                Logger::error('Caught Exception', $e->getMessage());
            }

        } else {
            UResponse::header('Messages', 'Bad Request', false, 400);
            echo 'Bad Request';
        }

    }


    public function isValid($action)
    {
        if (USession::exists('activeUser')) return true;
        return false;
    }

    public function onInvalidControl()
    {
        Startup::forward('/_default');
    }
}
