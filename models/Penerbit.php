<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "penerbit".
 *
 * @property int $id
 * @property string $nama
 * @property string $alamat
 * @property string $telepon
 * @property string $email
 */
class Penerbit extends \yii\db\ActiveRecord {
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'penerbit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['alamat'], 'string'],
            [['nama', 'telepon'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 2555],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id'      => 'ID',
            'nama'    => 'Nama',
            'alamat'  => 'Alamat',
            'telepon' => 'Telepon',
            'email'   => 'Email',
        ];
    }

    public static function getList() {
        return \yii\helpers\ArrayHelper::map(self::find()->all(), 'id', 'nama');
    }

    public function findAllBuku() {
        return Buku::find()
            ->andWhere(['id_penerbit' => $this->id])
            ->all();
    }

    public function getJumlahBuku() {
        return Buku::find()
            ->andWhere(['id_penerbit' => $this->id])
            ->count();
    }

    public static function getCount() {
        return static::find()->count();
    }

    public function getManybuku() {
        return $this->hasMany(Buku::class, ['id_penerbit' => 'id']);
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
