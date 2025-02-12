<?php

namespace App\Controllers;
use App\Core\Controller;
use App\Core\Form;
use App\Middlewares\AuthMiddleware;
use App\Utility\FileManager;
use App\Utility\FlashMessage;


class GalleryController extends Controller
{
    /**
     * Display a listing of the gallery to show on the dashboard with all the galleries of which user is a part.
     * @return void
     */
    public function index(): void
    {
        AuthMiddleware::requireLogin();
        $user = AuthMiddleware::getSessionUser();
        $galleryModel = $this->loadModel('GalleryModel');
        $galleries = $galleryModel->getUserGalleriesAndContent($user['id']);

        $data = [
            'title' => 'Mes Galleries',
            'galleries' => $galleries
        ];
        $this->loadView('gallery/index', $data);
    }

    /**
     * Show the form for creating a new gallery.
     * @return void
     */

    public function createGallery(): void
    {

        AuthMiddleware::requireLogin();

        $galleryForm = new Form('/gallery/create', 'POST');
        $galleryForm->addTextField(
            'name',
            '',
            $data['name'] ?? '',
            [
                'required' => true,
                'placeholder' => 'Gallery Name',
                'class' => 'form-control'
            ]
        )->addHiddenField(
                'csrf_token',
                AuthMiddleware::generateCsrfToken()
            )->addSubmitButton(
                'Create Gallery',
                ['class' => 'btn btn-primary']
            );


        $data = [
            'title' => 'Créer une Galerie',
            'form' => $galleryForm
        ];

        $this->loadView('gallery/create', $data);
    }

    /**
     * Store a newly created gallery in storage.
     * @return void
     */
    public function storeGallery(): void
    {
        AuthMiddleware::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            // redirect to the create gallery page
            $this->redirect('/gallery/create');
        }


        if (!AuthMiddleware::verifyCsrfToken($_POST['csrf_token'])) {
            // redirect to the create gallery page
            $this->redirect('/gallery/create');
        }

        $galleryModel = $this->loadModel('GalleryModel');

        // Sanitize the POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $user = $_SESSION['user'];

        try {
            $galleryId = $galleryModel->createGallery([
                'name' => $_POST['name'],
                'created_by' => $user['id']
            ]);
            $this->redirect('/gallery/' . $galleryId);
        } catch (\Exception $e) {
            $this->redirect('/gallery/create');
        }

    }

    /**
     * Display the specified gallery.
     * @param int $id
     * @return void
     */
    public function showGallery(int $id): void
    {
        AuthMiddleware::requireLogin();
        $user = AuthMiddleware::getSessionUser();

        $galleryModel = $this->loadModel('GalleryModel');
        $gallery = $galleryModel->getGallery($id, $user['id']);

        if (!$gallery || empty($gallery) || !$user) {
            $this->redirect('/gallery');
        }

        // Get the gallery photos
        $galleryPhotos = json_decode($gallery->galleryPhotos);

        if ((count($galleryPhotos) == 0 || (count($galleryPhotos) == 1 && empty($galleryPhotos[0]->id)) )) {
            $galleryPhotos = [];
        }

        // Get the gallery users
        $galleryUsers = $this->getGalleryUsers($id);
        $data = [
            'title' => $gallery->gallery_name,
            'galleryId' => $id,
            'galleryPhotos' => $galleryPhotos,
            'galleryUsers' => $galleryUsers,
        ];
        $this->loadView('gallery/single', $data);
    }

    /**
     * Show the form for uploading a new photo.
     * @return void
     */
    public function uploadPhotoForm(int $id): void
    {
        AuthMiddleware::requireLogin();
        $formAction = "/gallery/upload/{$id}";
        $photoForm = new Form($formAction, 'POST', 'multipart/form-data');
        $photoForm->addFileField(
            'photo',
            '',
            [
                'required' => true,
                'class' => 'button button-cta'
            ]
        )->addHiddenField(
                'csrf_token',
                AuthMiddleware::generateCsrfToken()
            )
            ->addSubmitButton('Upload Photo', ['class' => 'btn btn-primary']);

        $data = [
            'title' => 'Importer une photo',
            'form' => $photoForm
        ];
        // return $photoForm;

        $this->loadView('gallery/upload', $data);
    }

    /**
     * Store a newly created photo in storage.
     * @return void
     */
    public function storePhoto($id): void
    {
        AuthMiddleware::requireLogin();

        // if (!$_SERVER['REQUEST_METHOD'] !== 'POST') {
        //     $this->redirect('/gallery/upload/' . $id);
        // }

        if (!AuthMiddleware::verifyCsrfToken($_POST['csrf_token'])) {
            $this->redirect('/gallery/upload/' . $id);
        }

        $user = AuthMiddleware::getSessionUser();
        $galleryId = $id;
        $galleryModel = $this->loadModel('GalleryModel');
        $photo = $_FILES['photo'];
        $photoManager = FileManager::uploadGalleryPhoto($photo, $user['id'], $galleryId);

        $data = [
            'gallery_id' => $galleryId,
            'user_id' => $user['id'],
            'image_path' => $photoManager,
            'caption' => 'Photo caption',
            'is_public' => 1
        ];

        $galleryModel->createPhoto($data);
        $this->redirect('/gallery/' . $galleryId);
    }

    /**
     * Delete the specified photo from the gallery.
     * @param int $photoId
     * @return void
     */
    public function deletePhoto(int $photoId): void
    {
        AuthMiddleware::requireLogin();
        $galleryModel = $this->loadModel('GalleryModel');
        $user = $_SESSION['user'];
        $photo = $galleryModel->getPhoto($photoId, $user['id']);

        if (!$photo) {
            FlashMessage::add('Photo not found.', 'error');
            $this->redirect('/gallery');
        }

        
        $isPhotoDeleted = $galleryModel->deleteGalleryPhoto($photoId, $user['id']);
        if (!$isPhotoDeleted) {
            FlashMessage::add('Failed to delete photo.', 'error');
            $this->redirect('/gallery/' . $photo->gallery_id);
        }

        FileManager::deleteGalleryPhoto($photo->image_path);
        FlashMessage::add('Photo deleted successfully.', 'success');
        $this->redirect('/gallery/' . $photo->gallery_id);
    }

    /**
     * Get the users of a gallery
     * @param int $galleryId
     * @return mixed
     */
    private function getGalleryUsers(int $galleryId): mixed
    {
        AuthMiddleware::requireLogin();
        $galleryModel = $this->loadModel('GalleryModel');
        return $galleryModel->getGalleryUsers($galleryId);
    }
}
