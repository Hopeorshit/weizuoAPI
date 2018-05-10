<?php

namespace api\modules\v1\controllers;
use Yii;
use api\modules\v1\service\UserToken as UserTokenService;
use api\models\User as UserModel;
use api\models\Order as OrderModel;
require_once Yii::getAlias("@common/lib/encrypt/wxBizDataCrypt.php");
class UserController extends BaseActiveController
{
    public $modelClass='api\models\User';

    public function actionInfo_save()
    {
        $request=Yii::$app->request->bodyParams;//获取到参数
        $userName=$request['nickName'];
        $userAvatar=$request['avatarUrl'];
        $uid=UserTokenService::getCurrentTokenVar('uid');
        $userModel=UserModel::findOne($uid);
        $openID=$userModel->openid;
        if (!is_dir("image/{$openID}")) {
            mkdir("image/{$openID}");//根据用户的OpenID命名文件夹，username可能文件夹命名不支持
            chmod("image/{$openID}",0777);//Linux 系统要这样写
        }
        file_put_contents("image/{$openID}/avatar.jpg", file_get_contents($userAvatar));
        //TODO 暂时不考虑写入图片失败的情况
        $domain=YII::$app->params['domain'];
        $userAvatarLocal=$domain."image/{$openID}/avatar.jpg";
        $userModel->user_name=$userName;
        $userModel->avatar=$userAvatarLocal;
        $userModel->update();
        return[
        'userAvatar'=>$userAvatarLocal
       ];
    }

    public function actionInfo_get($uid){
        $userModel=UserModel::findOne($uid);
        return[
            'userName'=>$userModel->user_name,
            'userAvatar'=>$userModel->avatar
        ];
    }

    public function actionCount_zan($uid){
        $orderModel=OrderModel::find()->select('buyer')->where(['and','seller'=>$uid,['in','status',[1,2]]])->all();
        $result=[];
        foreach ($orderModel as $item){
            $user=UserModel::find()->where(['id'=>$item->buyer])->select('avatar')->asArray()->one();
            array_push($result,$user);
        }
        return $result;
    }

    public function actionInfo(){
        $uid=UserTokenService::getCurrentTokenVar('uid');
        $user=UserModel::findOne($uid);
        return $user;
    }

    public function actionEncrypt(){
        $session_key=UserTokenService::getCurrentTokenVar('session_key');
        $wxLogin=Yii::$app->params['wxLogin'];
        $wxAppID=$wxLogin['app_id'];//从配置文件中读取
        $request=Yii::$app->request->bodyParams;//获取到参数
        $encryptedData=$request['encryptedData'];
        $iv=$request['iv'];
        $wxBiz=new \WXBizDataCrypt($wxAppID,$session_key);
        $data='';
        $code=$wxBiz->decryptData($encryptedData,$iv,$data);
        $result=json_decode($data);
        return $result;
    }

}
