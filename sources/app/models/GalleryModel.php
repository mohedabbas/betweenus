<?php

namespace App\Models;

use App\Core\Model;

class GalleryModel extends Model
{

    /**
     *  Create a new instance of the GalleryModel
     */

    public function __construct()
    {
        parent::__construct('galleries');
    }


    /**
     * Get all the galleries of a user with the user id
     * @param int $userid
     * @return array
     */

    public function getGalleries(int $userid)
    {
        $sql = "SELECT * FROM $this->table WHERE created_by = :user_id  ORDER BY created_at DESC";
        $statement = $this->prepare($sql);

        $params = [
            'user_id' => $userid
        ];

        $this->execute($statement, $params);
        return $this->fetchAll($statement);

    }


    /**
     * Get a gallery by the id and the user id
     * @param int $id
     * @param int $userid
     * @return array
     */
    public function getGallery(int $id, int $userid)
    {
        $sql = "
        SELECT
            g.id AS gallery_id,
            g.name AS gallery_name,
            g.created_by,
            g.created_at AS gallery_created_at,
            JSON_ARRAYAGG(
                JSON_OBJECT(
                    'id', p.id,
                    'user_id', p.user_id,
                    'image_path', p.image_path,
                    'caption', p.caption,
                    'is_public', p.is_public,
                    'created_at', p.created_at
                )
            ) AS galleryPhotos
        FROM galleries g
        JOIN gallery_users gu ON gu.gallery_id = :id
        LEFT JOIN photos p ON p.gallery_id = :id
        WHERE gu.user_id = :user_id
        GROUP BY g.id, g.name, g.created_by, g.created_at
        ORDER BY g.created_at DESC;
        ";
        $statement = $this->prepare($sql);

        $params = [
            'id' => $id,
            'user_id' => $userid
        ];
        $this->execute($statement, $params);
        return $this->fetch($statement);
    }


    /**
     * Get all the galleries of a user with the user id and the photos in the gallery
     * @param int $userid
     * @return array
     */


    public function getUserGalleriesAndContent(int $userid): array
    {
        $sql = "SELECT
        g.id AS gallery_id,
        g.name AS gallery_name,
        g.created_by,
        g.created_at AS gallery_created_at,
        JSON_ARRAYAGG(
            JSON_OBJECT(
                'id', p.id,
                'user_id', p.user_id,
                'image_path', p.image_path,
                'caption', p.caption,
                'is_public', p.is_public,
                'created_at', p.created_at
            )
        ) AS galleryPhotos
    FROM galleries g
    JOIN gallery_users gu ON gu.gallery_id = g.id
    LEFT JOIN (
        SELECT
            p.*,
            ROW_NUMBER() OVER (PARTITION BY p.gallery_id ORDER BY p.created_at ASC) AS rn
        FROM photos p
    ) p ON p.gallery_id = g.id AND p.rn <= 4
    WHERE gu.user_id = :user_id
    GROUP BY g.id, g.name, g.created_by, g.created_at
    ORDER BY g.created_at DESC;
";
        $statement = $this->prepare($sql);
        $this->execute($statement, ['user_id' => $userid]);
        return $this->fetchAll($statement);
    }

    /**
     * Create a new gallery and insert it into the database with the user id.
     * @param array $data
     * @return int
     */

    public function createGallery(array $data): bool|int
    {
        // Insert data into the database where the user_id is the current user and the name is the name of the gallery
        // and also after creating the gallery we should insert the gallery_id, user_id and default permissions thats is the permission to can_upload and can view to the gallery_users table
        $sql = "INSERT INTO $this->table (name, created_by) VALUES (:name, :created_by)";
        $statement = $this->prepare($sql);
        $this->execute($statement, $data);

        $galleryId = $this->pdo->lastInsertId();

        $sql = "INSERT INTO gallery_users (gallery_id, user_id, can_upload, can_view) VALUES (:gallery_id, :user_id, 1, 1)";
        $statement = $this->prepare($sql);
        $this->execute($statement, [
            'gallery_id' => $galleryId,
            'user_id' => $data['created_by']
        ]);

        return $galleryId;
    }

    public function deleteGalleryPhoto(int $photoId, int $userId)
    {
        $sql = "DELETE FROM photos WHERE id = :photo_id AND user_id = :user_id";
        $statement = $this->prepare($sql);
        $params = [
            'photo_id' => $photoId,
            'user_id' => $userId
        ];
        $this->execute($statement, $params);
        // Check if the photo was deleted
        return $statement->rowCount() > 0;
    }


    public function getPhoto(int $photoId, int $userId)
    {
        $sql = "SELECT * FROM photos WHERE id = :photo_id";
        $statement = $this->prepare($sql);
        $params = [
            'photo_id' => $photoId
        ];
        $this->execute($statement, $params);
        return $this->fetch($statement);
    }


    public function createPhoto(array $data): int
    {
        $sql = "INSERT INTO photos (gallery_id, user_id, image_path, caption, is_public) VALUES (:gallery_id, :user_id, :image_path, :caption, :is_public)";
        $statement = $this->prepare($sql);
        $params = [
            'gallery_id' => $data['gallery_id'],
            'user_id' => $data['user_id'],
            'image_path' => $data['image_path'],
            'caption' => $data['caption'],
            'is_public' => $data['is_public']
        ];
        $this->execute($statement, $data);
        return $this->pdo->lastInsertId();
    }



    public function getGalleryUsers(int $galleryId)
    {
        $sql = " SELECT *
        FROM gallery_users gu
                LEFT JOIN users u ON u.id = gu.user_id
                WHERE gallery_id = :gallery_id
        ";
        $statement = $this->prepare($sql);
        $this->execute($statement, ['gallery_id' => $galleryId]);
        return $this->fetchAll($statement);
    }


    public function getUsersNotInGallery(int $galleryId)
    {
        $sql = "SELECT u.id, u.first_name,u.last_name, u.profile_image FROM users u WHERE id NOT IN (SELECT user_id FROM gallery_users WHERE gallery_id = :gallery_id) ORDER BY u.created_at ASC LIMIT 5";
        $statement = $this->prepare($sql);
        $this->execute($statement, ['gallery_id' => $galleryId]);
        return $this->fetchAll($statement);
    }

    public function getConnectedUserRole(int $userId, int $galleryId)
    {
        $sql = "SELECT * FROM gallery_users WHERE user_id = :user_id AND gallery_id = :gallery_id";
        $statement = $this->prepare($sql);
        $this->execute($statement, ['user_id' => $userId, 'gallery_id' => $galleryId]);
        return $this->fetch($statement);
    }


    public function addUsersinGalleryById(int $userId, $galleryId){
        $sql = "
            INSERT INTO gallery_users (gallery_id, user_id, can_upload, can_view, is_owner) VALUES (:gallery_id, :user_id, 1, 1, 0)
        ";
        $statement = $this->prepare($sql);
        $this->execute($statement, ['gallery_id' => $galleryId, 'user_id' => $userId]);
        return $this->pdo->lastInsertId();
    }
}
