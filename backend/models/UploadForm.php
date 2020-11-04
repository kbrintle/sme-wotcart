<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use common\components\helpers\UploadHelper;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $file;
    public $group_id;

    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => true],
        ];
    }

    public function uploadCSV() {
        if ($this->validate()) {
            $filename = UploadHelper::hashFile($this->file->baseName, $this->file->extension);
            $location = Yii::getAlias("@frontend") . '/web/uploads/import';

            if ($this->file->saveAs($location.$filename)) {
                return $location.$filename;
            }
        }
        return false;
    }

    public function uploadImage() {
        if ($this->validate()) {
            $filename = sha1($this->file->baseName.time()).'.'.$this->file->extension;
            $location = Yii::getAlias("@frontend") . '/web/uploads/products/';

            if ($this->file->saveAs($location.$filename)) {
                return $filename;
            }
        }
    }

    public function uploadBanner() {
        if ($this->validate()) {
            $filename = sha1($this->file->baseName.time()).'.'.$this->file->extension;
            $location = Yii::getAlias("@frontend") . '/web/uploads/banner/';

            if ($this->file->saveAs($location.$filename)) {
                return $filename;
            }
        }
    }

    public function uploadZip() {
        if ($this->validate()) {
            $filename = sha1($this->file->baseName.time()).'.'.$this->file->extension;
            $location = Yii::getAlias("@frontend") . '/web/uploads/zips/';

            if ($this->file->saveAs($location.$filename)) {
                return $location.$filename;
            }
        }
    }

    public function uploadCoupon() {
        if ($this->validate()) {
            $filename = sha1($this->file->baseName.time()).'.'.$this->file->extension;
            $location = Yii::getAlias("@frontend") . '/web/uploads/coupons/';

            if ($this->file->saveAs($location.$filename)) {
                return $filename;
            }
        }
    }
}