<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Items".
 *
 * @property integer $Item_Id
 * @property string $XH_ID
 * @property string $Item_Name
 * @property string $Item_Intro
 * @property integer $Status
 * @property integer $ShowPublic
 *
 * @property UserTb $xH
 */
class Items extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Items';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['XH_ID', 'Item_Name', 'Status', 'ShowPublic'], 'required'],
            [['Status', 'ShowPublic'], 'integer'],
            [['XH_ID'], 'string', 'max' => 10],
            [['Item_Name'], 'string', 'max' => 16],
            [['Item_Intro'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Item_Id' => 'Item  ID',
            'XH_ID' => 'Xh  ID',
            'Item_Name' => 'Item  Name',
            'Item_Intro' => 'Item  Intro',
            'Status' => 'Status',
            'ShowPublic' => 'Show Public',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXH()
    {
        return $this->hasOne(UserTb::className(), ['XH_ID' => 'XH_ID']);
    }
}
