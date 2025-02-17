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
            'title' => 'Mes Galeries',
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

        $galleryForm = new Form('/gallery/create', 'POST', '', 'w-100');
        $galleryForm->addTextField(
            'name',
            '',
            $data['name'] ?? '',
            [
                'required' => true,
                'placeholder' => 'Nom de la Galerie',
                'class' => 'mb-6'
            ]
        )->addHiddenField(
                'csrf_token',
                $_SESSION['csrf_token'] ?? AuthMiddleware::generateCsrfToken()
            )->addSubmitButton(
                'Créer',
                ['class' => 'button form__button']
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
            FlashMessage::add('Méthode non autorisée.', 'error');
            // redirect to the create gallery page
            $this->redirect('/gallery/create');
        }


        if (!AuthMiddleware::verifyCsrfToken($_POST['csrf_token'])) {
            // redirect to the create gallery page
            FlashMessage::add('L\'authentification a échoué.', 'error');
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

        if ((count($galleryPhotos) == 0 || (count($galleryPhotos) == 1 && empty($galleryPhotos[0]->id)))) {
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
            ->addSubmitButton('Upload Photo', ['class' => 'button']);

        $data = [
            'title' => 'Importer une photo',
            'form' => $photoForm
        ];
        $this->loadView('gallery/upload', $data);
    }

    /**
     * Store a newly created photo in storage.
     * @return void
     */
    public function storePhoto($id): void
    {
        AuthMiddleware::requireLogin();

        // Vérification du token CSRF
        if (!isset($_POST['csrf_token']) || !AuthMiddleware::verifyCsrfToken($_POST['csrf_token'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Token CSRF invalide']);
            return;
        }

        $user = AuthMiddleware::getSessionUser();
        $galleryId = $id;
        $galleryModel = $this->loadModel('GalleryModel');

        // Gestion de l'upload multiple via AJAX (clé 'files')
        if (isset($_FILES['files']) && !empty($_FILES['files']['name'][0])) {
            $uploadedFiles = $_FILES['files'];
            $fileCount = count($uploadedFiles['name']);
            $results = [];
            for ($i = 0; $i < $fileCount; $i++) {
                $file = [
                    'name' => $uploadedFiles['name'][$i],
                    'type' => $uploadedFiles['type'][$i],
                    'tmp_name' => $uploadedFiles['tmp_name'][$i],
                    'error' => $uploadedFiles['error'][$i],
                    'size' => $uploadedFiles['size'][$i]
                ];
                $photoPath = FileManager::uploadGalleryPhoto($file, $user['id'], $galleryId);
                $data = [
                    'gallery_id' => $galleryId,
                    'user_id' => $user['id'],
                    'image_path' => $photoPath,
                    'caption' => 'Photo caption',
                    'is_public' => 1
                ];
                $galleryModel->createPhoto($data);
                $results[] = $data;
            }
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'uploaded' => $results]);
            return;
        }
        // Cas d'upload d'un seul fichier avec la clé 'photo'
        elseif (isset($_FILES['photo']) && !empty($_FILES['photo']['name'])) {
            $photo = $_FILES['photo'];
            $photoPath = FileManager::uploadGalleryPhoto($photo, $user['id'], $galleryId);
            $data = [
                'gallery_id' => $galleryId,
                'user_id' => $user['id'],
                'image_path' => $photoPath,
                'caption' => 'Photo caption',
                'is_public' => 1
            ];
            $galleryModel->createPhoto($data);
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            return;
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Aucun fichier reçu']);
            return;
        }
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
            FlashMessage::add('Photo non trouvée.', 'error');
            $this->redirect('/gallery');
        }

        $isPhotoDeleted = $galleryModel->deleteGalleryPhoto($photoId, $user['id']);
        if (!$isPhotoDeleted) {
            FlashMessage::add('Échec de suppression de la photo.', 'error');
            $this->redirect('/gallery/' . $photo->gallery_id);
        }

        FileManager::deleteGalleryPhoto($photo->image_path);
        FlashMessage::add('Photo supprimée avec succès.', 'success');
        $this->redirect('/gallery/' . $photo->gallery_id);
    }


    /**
     * Empty the specified gallery.
     * @param int $galleryId
     * @return void
     */

    public function emptyGallery(int $galleryId): void
    {
        $galleryModel = $this->loadModel('GalleryModel');
        $userId = AuthMiddleware::getSessionUser()['id'];

        $isOwner = $galleryModel->checkOwner($userId);

        if (!$isOwner) {
            FlashMessage::add('Vous n\'avez pas la permission de vider la galerie.', 'error');
            $this->redirect('/gallery/' . $galleryId);
        }

        $isEmptied = $galleryModel->emptyGallery($galleryId, $userId);

        if (!$isEmptied) {
            FlashMessage::add('Échec pour vider la galerie.', 'error');
            $this->redirect('/gallery/' . $galleryId);
        }

        FileManager::emptyGalleryPhotos($galleryId);
        FlashMessage::add('La galerie a été vidée.', 'success');
        $this->redirect('/gallery/' . $galleryId);
    }

    /**
     * Delete the specified gallery.
     * @param int $galleryId
     * @return void
     */

    public function deleteGallery(int $galleryId): void
    {
        $galleryModel = $this->loadModel('GalleryModel');
        $userId = AuthMiddleware::getSessionUser()['id'];
        $isOwner = $galleryModel->checkOwner($userId);
        if (!$isOwner) {
            FlashMessage::add('Vous n\'avez pas la permission d\'effectuer cette action.', 'error');
            $this->redirect('/gallery/' . $galleryId);
        }

        $isDeleted = $galleryModel->deleteGalleryById($galleryId);
        if (!$isDeleted) {
            FlashMessage::add('Échec de la suppression de la galerie.', 'error');
            $this->redirect('/gallery/' . $galleryId);
        }

        FileManager::emptyGalleryPhotos($galleryId);
        FlashMessage::add('La galerie a été supprimée.', 'success');
        $this->redirect('/gallery');
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
