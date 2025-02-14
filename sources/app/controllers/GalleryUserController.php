<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Form;
use App\Middlewares\AuthMiddleware;
use App\Utility\FlashMessage;
use App\Utility\Mailer;

class GalleryUserController extends Controller
{

    /**
     * Show the form to add users in gallery and the users that can be added
     * @param int $galleryId
     */
    public function addUsersInGallery(int $galleryId): void
    {
        AuthMiddleware::requireLogin();

        $formAction = "/gallery/addusers/{$galleryId}";
        $addUsersForm = new Form($formAction, 'POST');
        $addUsersForm
            ->addTextField('email', 'Email', '', [
                'required' => true,
                'placeholder' => 'Email',
                'class' => 'form-group'
            ])
            ->addHiddenField('csrf_token', $_SESSION['csrf_token'])
            ->addSubmitButton('Search User', [
                'class' => 'button button-cta',
                'name' => 'search_user'
            ]);

        // Fetch existing users in the gallery
        $users = $this->getMembersInGallery($galleryId);

        // Remove the current logged-in user from the list
        $currentUserId = AuthMiddleware::getSessionUser()['id'];
        $users = array_filter($users, fn($user) => $user->id !== $currentUserId);
        $galleryModel = $this->loadModel('GalleryModel');
        // Handle POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_user'])) {
            // Validate CSRF token
            if (!AuthMiddleware::verifyCsrfToken($_POST['csrf_token'])) {
                FlashMessage::add('Invalid CSRF token', 'error');
                $this->redirect("/gallery/addusers/$galleryId");
            }

            // Validate email input
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                FlashMessage::add('Invalid email format', 'error');
                $this->redirect("/gallery/addusers/$galleryId");
            }

            // Check if the user exists in the database and is not part of the gallery.
            $getUser = $galleryModel->getUsersNotInGallery($galleryId, $email);

            if (!$getUser || empty($getUser)) {
                FlashMessage::add('Aucun utilisateur trouvé avec cet e-mail ou l’utilisateur est déjà ajouté', 'error');
                $this->redirect("/gallery/addusers/$galleryId");
            }

            FlashMessage::add('Utilisateur Trouver', 'success');

            // Add new user to the list
            $getUser->is_newUser = true;

            // Add the new user to the beginning of the array to display it first.
            array_unshift($users, $getUser);
        }

        // Render the view with data
        $data = [
            'title' => 'Ajouter les utilisateurs à la galerie',
            'form' => $addUsersForm,
            'galleryId' => $galleryId,
            'users' => $users
        ];
        $this->loadView('gallery/addUsers', $data);
    }

    /**
     * Add a user in the gallery and send an email to the user
     * @param int $userid
     */
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


    /**
     * Remove a user from the gallery
     * @param int $userid
     */
    public function removeUserFromGallery($userid)
    {
        $galleryId = $_GET['galleryid'];
        $galleryModel = $this->loadModel('GalleryModel');
        $lastID = $galleryModel->removeUserFromGalleryById($userid, $galleryId);
        if ($lastID) {
            // If the user is added successfully
            FlashMessage::add('User removed successfully', 'success');
            $this->redirect("/gallery/addusers/$galleryId");
        } else {
            FlashMessage::add('Error removing user', 'error');
            $this->redirect("/gallery/addusers/$galleryId");
        }
    }



    /**
     * Add a user in the gallery and send an email to the user
     * @param int $userid
     * @deprecated This method is deprecated. Use addUserAndSendMail instead.
     */
    private function getUsersToAddInGallery(int $galleryId): array
    {
        AuthMiddleware::requireLogin();

        $galleryModel = $this->loadModel('GalleryModel');
        $user = AuthMiddleware::getSessionUser();
        $roles = $this->getConnectedUserRole($user['id'], $galleryId);

        if (!$roles || $roles->is_owner !== '1') {
            FlashMessage::add('Dont have necessary permissions.', 'error');
            $this->redirect('/gallery/' . $galleryId);
        }

        $users = $galleryModel->getUsersNotInGallery($galleryId);

        return $users;
    }

    /**
     * Get the members in the gallery to display in the view
     * @param int $galleryId
     */
    public function getMembersInGallery(int $galleryId): array
    {
        AuthMiddleware::requireLogin();

        $galleryModel = $this->loadModel('GalleryModel');
        $user = AuthMiddleware::getSessionUser();
        $roles = $this->getConnectedUserRole($user['id'], $galleryId);

        if (!$roles || $roles->is_owner !== '1') {
            FlashMessage::add('Dont have necessary permissions.', 'error');
            $this->redirect('/gallery/' . $galleryId);
        }

        $users = $galleryModel->getGalleryUsers($galleryId);

        return $users;
    }

    /**
     * Get the connected user role by the user id and the gallery id to check if it can add new users
     * @param int $userId
     * @param int $galleryId
     */
    private function getConnectedUserRole(int $userId, int $galleryId): mixed
    {
        $galleryModel = $this->loadModel('GalleryModel');
        $role = $galleryModel->getConnectedUserRole($userId, $galleryId);
        return $role;
    }


}
