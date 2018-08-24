<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kategori".
 *
 * @property int $id
 * @property string $nama
 */
class Kategori extends \yii\db\ActiveRecord {
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'kategori';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['nama'], 'required'],
            [['nama'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id'   => 'ID',
            'nama' => 'Nama',
        ];
    }

    public static function getList() {
        return \yii\helpers\ArrayHelper::map(self::find()->all(), 'id', 'nama');
    }

    public function findAllBuku() {
        return Buku::find()
            ->andWhere(['id_kategori' => $this->id])
            ->all();
    }

    public function getJumlahBuku() {
        return Buku::find()
            ->andWhere(['id_kategori' => $this->id])
            ->count();
    }

    public static function getCount() {
        return static::find()->count();
    }

    public function getManybuku() {
        return $this->hasMany(Buku::class, ['id_kategori' => 'id']);
    }

    // Menjumlah semua data buku yang berkaitan dengan id_***.
    public static function getGrafikList() {
        $data = [];
        foreach (static::find()->all() as $kategori) {
            $data[] = [$kategori->nama, (int) $kategori->getManyBuku()->count()];
        }
        return $data;
    }
}
