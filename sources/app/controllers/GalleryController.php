<?php

namespace App\Controllers;
use App\Core\Controller;
use App\Core\Form;
use App\Utility\FileManager;




class GalleryController extends Controller
{

    /**
     * Display a listing of the gallery to show on the dashboard with all the galleries of which user is a part.
     * @return void
     */
    public function index(): void
    {
        $user = $_SESSION['user'] ?? null;
        if (!$user or empty($user)) {
            header('Location: /login');
        }
        $galleryModel = $this->loadModel('GalleryModel');
        $galleries = $galleryModel->getUserGalleriesAndContent($user['id']);

        $data = [
            'title' => 'My Galleries',
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
        )->addSubmitButton('Create Gallery', ['class' => 'btn btn-primary']);


        $data = [
            'title' => 'Create Gallery',
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

        $galleryModel = $this->loadModel('GalleryModel');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            // redirect to the create gallery page
            header('Location: /gallery/create');
        }

        // Sanitize the POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $user = $_SESSION['user'];

        try {
            $galleryModel->createGallery([
                'name' => $_POST['name'],
                'created_by' => $user['id']
            ]);
            // redirect to the gallery page
            header('Location: /gallery');
        } catch (\Exception $e) {
            // redirect to the create gallery page
            header('Location: /gallery/create');
        }

    }

    /**
     * Display the specified gallery.
     * @param int $id
     * @return void
     */
    public function showGallery(int $id): void
    {
        $galleryModel = $this->loadModel('GalleryModel');

        $user = $_SESSION['user'];
        $gallery = $galleryModel->getGallery($id, $user['id']);

        if (!$gallery || empty($gallery) || !$user) {
            // redirect to the gallery page
            header('Location: /gallery');
        }

        // Get the gallery photos
        $galleryPhotos = json_decode($gallery->galleryPhotos);

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
     * Get the users of a gallery
     * @param int $galleryId
     * @return mixed
     */
    private function getGalleryUsers(int $galleryId): mixed
    {
        $galleryModel = $this->loadModel('GalleryModel');
        return $galleryModel->getGalleryUsers($galleryId);
    }

    /**
     * Show the form for uploading a new photo.
     * @return void
     */
    public function uploadPhotoForm(int $id): void
    {
        $formAction = "/gallery/upload/{$id}";
        $photoForm = new Form($formAction, 'POST', 'multipart/form-data');
        $photoForm->addFileField(
            'photo',
            '',
            [
                'required' => true,
                'class' => 'button button-cta'
            ]
        )->addSubmitButton('Upload Photo', ['class' => 'btn btn-primary']);

        $data = [
            'title' => 'Upload Photo',
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
        if (!$_SERVER['REQUEST_METHOD'] !== 'POST') {
            // redirect to the upload photo page
            header('Location: /gallery/upload');
        }
        // Check if the user is logged in
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
        }
        $user = $_SESSION['user'];
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
        header("Location: /gallery/" . $galleryId);
    }

    /**
     * Delete the specified photo from the gallery.
     * @param int $photoId
     * @return void
     */
    public function deletePhoto(int $photoId): void
    {
        $galleryModel = $this->loadModel('GalleryModel');
        $user = $_SESSION['user'];
        $photo = $galleryModel->getPhoto($photoId, $user['id']);

        $galleryModel->deleteGalleryPhoto($photoId, $user['id']);
        FileManager::deleteGalleryPhoto($photo->image_path);

        header('Location: /gallery');
    }

}
