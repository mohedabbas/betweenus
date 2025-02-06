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
        $sql = "SELECT * FROM $this->table WHERE id = :id AND user_id = :user_id";
        $statement = $this->prepare($sql);
        $statement->bindParam('id', $id);
        $statement->bindParam('user_id', $userid);
        $this->execute($statement);
        return $this->fetch($statement);
    }


    /**
     * Get all the galleries of a user with the user id and the photos in the gallery
     * @param int $userid
     * @return array
     */


    public function getUserGalleriesAndContent(int $userid): array
    {
        // $sql = "
        //     SELECT photos.* FROM galleries
        //     JOIN gallery_users ON gallery_users.gallery_id = galleries.id
        //     JOIN photos ON photos.gallery_id = galleries.id
        //     WHERE gallery_users.user_id = :user_id
        //     ORDER BY galleries.created_at DESC
        // ";

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
        JOIN gallery_users gu ON gu.gallery_id = g.id
        LEFT JOIN photos p ON p.gallery_id = g.id
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

}