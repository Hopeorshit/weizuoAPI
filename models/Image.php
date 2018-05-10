<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "image".
 *
 * @property integer $id
 * @property integer $album_id
 * @property string $url
 * @property string $created
 * @property string $updated
 */
class Image extends BaseModel{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'image';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'album_id' => 'Album ID',
            'url' => 'Url',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
