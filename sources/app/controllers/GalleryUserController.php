<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Form;
use App\Middlewares\AuthMiddleware;
use App\Utility\FlashMessage;


class GalleryUserController extends Controller{
    public function addUsersInGalleryForm(int $galleryId): void
    {
        AuthMiddleware::requireLogin();

        $formAction = "/gallery/addusers/{$galleryId}";
        $addUsersForm = new Form($formAction, 'POST');
        $addUsersForm
            ->addTextField(
                'email',
                'Email',
                '',
                [
                    'required' => true,
                    'placeholder' => 'Email',
                    'class' => 'form-control'
                ]
            )
            ->addHiddenField
            (
                'csrf_token',
                AuthMiddleware::generateCsrfToken()
            )->addSubmitButton(
                'Search User',
                ['class' => 'button button-cta']
            );
        $data = [
            'title' => 'Add Users',
            'form' => $addUsersForm,
            'galleryId' => $galleryId
        ];
        $this->loadView('gallery/addUsers', $data);
    }


    public function addUsersInGallery(int $galleryId): void
    {
        AuthMiddleware::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/gallery/addusers/' . $galleryId);
        }

        if (!AuthMiddleware::verifyCsrfToken($_POST['csrf_token'])) {
            $this->redirect('/gallery/addusers/' . $galleryId);
        }

        $galleryModel = $this->loadModel('GalleryModel');
        $authModel = $this->loadModel('AuthModel');
        $user = AuthMiddleware::getSessionUser();

        $roles = $this->getConnectedUserRole($user['id'], $galleryId);


        if (!$roles || $roles->can_upload !== '1') {
            $this->redirect('/gallery/' . $galleryId);
        }

        $email = $_POST['email'];
        $user = $authModel->findUserByUsernameOrEmail($email);

        if (!$user) {
            FlashMessage::add('User not found', 'error');
            $this->redirect("/gallery/addusers/{$galleryId}");
        }

        var_dump(
            $user
        );
        // $galleryModel->addUserToGallery($galleryId, $user->id);
        // $this->redirect('/gallery/' . $galleryId);
    }

    private function getConnectedUserRole(int $userId, int $galleryId): mixed
    {
        $galleryModel = $this->loadModel('GalleryModel');
        $role = $galleryModel->getConnectedUserRole($userId, $galleryId);
        return $role;
    }


    public function getUsersToAddInGallery(int $galleryId): void
    {
        AuthMiddleware::requireLogin();

        $authModel = $this->loadModel('AuthModel');
        $galleryModel = $this->loadModel('GalleryModel');
        $user = AuthMiddleware::getSessionUser();

        $roles = $this->getConnectedUserRole($user['id'], $galleryId);

        if (!$roles || $roles->can_upload !== '1') {
            $this->redirect('/gallery/' . $galleryId);
        }

        $users = $galleryModel->getUsersNotInGallery($galleryId);
        $data = [
            'title' => 'Add Users',
            'users' => $users,
            'galleryId' => $galleryId
        ];

        var_dump($users);
        // $this->loadView('gallery/addUsers', $data);
    }
}
