<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "articlerecord".
 *
 * @property integer $id
 * @property integer $art_info
 * @property string $action
 * @property string $dotime
 * @property string $user
 * @property integer $status
 */
class Articlerecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'articlerecord';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['art_info', 'status'], 'integer'],
            [['action', 'dotime'], 'string', 'max' => 20],
            [['user'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'art_info' => 'Art Info',
            'action' => 'Action',
            'dotime' => 'Dotime',
            'user' => 'User',
            'status' => 'Status',
        ];
    }
}
