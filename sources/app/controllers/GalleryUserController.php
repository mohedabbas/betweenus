<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Form;
use App\Middlewares\AuthMiddleware;
use App\Utility\FlashMessage;
use App\Utility\Mailer;

class GalleryUserController extends Controller
{
    public function addUsersInGallery(int $galleryId): void
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
            ->addHiddenField(
                'csrf_token',
                AuthMiddleware::generateCsrfToken()
            )->addSubmitButton(
                'Search User',
                [
                    'class' => 'button button-cta',
                    'name' => 'search_user'
                ]
            );

        $users = $this->getUsersToAddInGallery($galleryId);

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search_user'])) {
            $email = $_POST['email'];



            $authModel = $this->loadModel('AuthModel');

            $getUser = $authModel->findUserByUsernameOrEmail($email);

            if (!$getUser) {
                FlashMessage::add('No user found with the given mail', 'error');
                $this->redirect("/gallery/addusers/$galleryId");
            }
            unset($users);
            $users[] = $getUser;
        }

        $data = [
            'title' => 'Add Users',
            'form' => $addUsersForm,
            'galleryId' => $galleryId,
            'users'  => $users
        ];
        $this->loadView('gallery/addUsers', $data);
    }

    public function getUsersToAddInGallery(int $galleryId): array
    {
        AuthMiddleware::requireLogin();

        $authModel = $this->loadModel('AuthModel');
        $galleryModel = $this->loadModel('GalleryModel');
        $user = AuthMiddleware::getSessionUser();
        $roles = $this->getConnectedUserRole($user['id'], $galleryId);

        if (!$roles || $roles->is_owner !== '1') {
            $this->redirect('/gallery/' . $galleryId);
        }

        $users = $galleryModel->getUsersNotInGallery($galleryId);

        return $users;
    }

    public function addUserAndSendMail($userid)
    {
        $galleryId = $_GET['galleryid'];
        $galleryModel = $this->loadModel('GalleryModel');
        $lastID = $galleryModel->addUsersinGalleryById($userid, $galleryId);


        if ($lastID) {    // If the user is added successfully
            $mailer = new Mailer();
            $user = $this->loadModel('AuthModel')->findUserById($userid);
            $mailer->sendMail($user->email, 'Invitation to join gallery', 'You have been invited to join a gallery. Please login to your account to accept the invitation.');
            FlashMessage::add('User added successfully', 'success');
            $this->redirect("/gallery/addusers/$galleryId");
        } else {
            FlashMessage::add('Error adding user', 'error');
            $this->redirect("/gallery/addusers/$galleryId");
        }
    }

    private function getConnectedUserRole(int $userId, int $galleryId): mixed
    {
        $galleryModel = $this->loadModel('GalleryModel');
        $role = $galleryModel->getConnectedUserRole($userId, $galleryId);
        return $role;
    }
}
