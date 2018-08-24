<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "buku".
 *
 * @property int $id
 * @property string $nama
 * @property string $tahun_terbit
 * @property int $id_penulis
 * @property int $id_penerbit
 * @property int $id_kategori
 * @property string $sinopsis
 * @property string $sampul
 * @property string $berkas
 */
class Buku extends \yii\db\ActiveRecord {
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'buku';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['nama'], 'required'],
            [['tahun_terbit'], 'safe'],
            [['id_penulis', 'id_penerbit', 'id_kategori'], 'integer'],
            [['sinopsis'], 'string'],
            [['nama', 'berkas'], 'string', 'max' => 255],
            [['sampul'], 'file', 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 20],
            [['berkas'], 'file', 'extensions' => 'doc, docx, xls, xlsx, pdf, ppt'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id'           => 'ID',
            'nama'         => 'Nama',
            'tahun_terbit' => 'Tahun Terbit',
            'id_penulis'   => 'Penulis',
            'id_penerbit'  => 'Penerbit',
            'id_kategori'  => 'Kategori',
            'sinopsis'     => 'Sinopsis',
            'sampul'       => 'Sampul',
            'berkas'       => 'Berkas',
        ];
    }

    public function getPenulis() {
        //memanggil id penulis yang ada di table buku dan dimasukan kedalam variable $model
        $model = Penulis::findOne($this->id_penulis);

        //jika id penulis yang ada di table buku = ada, maka return dan panggil namanya
        if ($model != null) {
            return $model->nama;
        } else {
            return null;
        }

        // return $this->hasOne(Penulis::className(), ['id' => 'id_penulis']);
    }

    public function getPenerbit() {
        //memanggil id penerbit yang ada di table buku dan dimasukan kedalam variable $model
        $model = Penerbit::findOne($this->id_penerbit);

        //jika id penerbit yang ada di table buku = ada, maka return dan panggil namanya
        if ($model != null) {
            return $model->nama;
        } else {
            return null;
        }

        // return $this->hasOne(Penerbit::className(), ['id' => 'id_penerbit']);
    }

    public function getKategori() {
        //memanggil id kategori yang ada di table buku dan dimasukan kedalam variable $model
        $model = Kategori::findOne($this->id_kategori);

        //jika id kategori yang ada di table buku = ada, maka return dan panggil namanya
        if ($model != null) {
            return $model->nama;
        } else {
            return null;
        }

        // return $this->hasOne(Kategori::className(), ['id' => 'id_kategori']);
    }

    public static function getCount() {
        return static::find()->count();
    }
}
