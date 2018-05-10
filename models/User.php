<?php

namespace api\models;

class User extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_name' => 'User Name',
            'openid' => 'Openid',
            'unionid' => 'Unionid',
            'avatar' => 'Avatar',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
    public function getUserByOpenID($OpenID){
        return self::find()->where(['openid'=>$OpenID])->one();
    }

    public function getUserByOpenfID($OpenIDF){
        return self::find()->where(['openidf'=>$OpenIDF])->one();
    }

    public function getUserByUnionID($unionID){
        return self::find()->where(['unionid'=>$unionID])->one();
    }
    /**
     * @inheritdoc
     */
    public function fields()//TODO 为什么不起作用呢
    {
        $fields = parent::fields();
        // 去掉一些包含敏感信息的字段
        unset($fields['openid'], $fields['unionid']);
        return $fields;
    }
}
