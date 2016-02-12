<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kbmember".
 *
 * @property string $wybs
 * @property integer $jxrwid
 * @property integer $nf
 * @property string $xn
 * @property string $xq
 * @property string $jzrq
 * @property integer $xqj
 * @property integer $ksxj
 * @property integer $jsxj
 * @property integer $qsz
 * @property integer $jsz
 * @property string $skzc
 * @property string $xh
 * @property string $xkkh
 * @property string $kcmc
 * @property string $skls
 * @property string $jsbh
 * @property string $jsmc
 * @property integer $skcd
 * @property string $yxz
 */
class Kbmember extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kbmember';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wybs'], 'required'],
            [['jxrwid', 'nf', 'xqj', 'ksxj', 'jsxj', 'qsz', 'jsz', 'skcd'], 'integer'],
            [['wybs'], 'string', 'max' => 320],
            [['xn', 'xq', 'jzrq', 'skzc', 'xkkh', 'kcmc', 'skls', 'jsbh', 'jsmc', 'yxz'], 'string', 'max' => 255],
            [['xh'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'wybs' => 'Wybs',
            'jxrwid' => 'Jxrwid',
            'nf' => 'Nf',
            'xn' => 'Xn',
            'xq' => 'Xq',
            'jzrq' => 'Jzrq',
            'xqj' => 'Xqj',
            'ksxj' => 'Ksxj',
            'jsxj' => 'Jsxj',
            'qsz' => 'Qsz',
            'jsz' => 'Jsz',
            'skzc' => 'Skzc',
            'xh' => 'Xh',
            'xkkh' => 'Xkkh',
            'kcmc' => 'Kcmc',
            'skls' => 'Skls',
            'jsbh' => 'Jsbh',
            'jsmc' => 'Jsmc',
            'skcd' => 'Skcd',
            'yxz' => 'Yxz',
        ];
    }
}
