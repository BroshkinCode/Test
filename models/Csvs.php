<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "csvs".
 *
 * @property int $id
 * @property int $uid
 * @property string $file_name
 * @property string $fields
 * @property int $uploaded_at
 */
class Csvs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'csvs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'file_name'], 'required'],
            [['uid', 'uploaded_at'], 'integer'],
            [['fields'], 'string'],
            [['file_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'file_name' => 'File Name',
            'fields' => 'Fields',
            'uploaded_at' => 'Uploaded At',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'uploaded_at',
                'updatedAtAttribute' => 'uploaded_at',
                'value' => time(),
            ]
        ];
    }
}
