<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "moment_top".
 *
 * @property string $id
 * @property string $moment_id
 * @property integer $status
 */
class MomentTop extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'moment_top';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['moment_id'], 'required'],
            [['moment_id', 'status'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'moment_id' => 'Moment ID',
            'status' => 'Status',
        ];
    }
}
