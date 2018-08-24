<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BukuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = 'Bukus';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="buku-index">

    <h1><?=Html::encode($this->title)?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?=Html::a('Create Buku', ['create'], ['class' => 'btn btn-dark'])?>

        <?=Html::a('Export Word', ['buku/export-word'], ['class' => 'btn btn-info', 'title' => 'Export table buku berbentuk Word']);?>
        <?=Html::a('Export Excel', ['buku/export-excel'], ['class' => 'btn btn-success', 'title' => 'Export table buku berbentuk Excel']);?>
        <?=Html::a('Export PDF', ['buku/export-mpdf'], ['class' => 'btn btn-danger', 'title' => 'Export table buku berbentuk Pdf']);?>
        <?=Html::a('BA Klarifikasi & Negosiasi', ['buku/export-berita-acara'], ['class' => 'btn btn-info', 'title' => 'Export Word untuk Berita Acara Klarifikasi dan Negosiasi']);?>

    </p>

    <?=GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns'      => [
        ['class' => 'yii\grid\SerialColumn'],

        'id',
        'nama',
        'tahun_terbit',
        [
            'attribute' => 'id_penulis',
            'value'     => function ($data) {
                return $data->getPenulis();
            },
        ],
        [
            'attribute' => 'id_penerbit',
            'value'     => function ($data) {
                return $data->getPenerbit();
            },
        ],
        [
            'attribute' => 'id_kategori',
            'value'     => function ($data) {
                return $data->getKategori();
            },
        ],
        // 'sinopsis:ntext',

        [
            'attribute' => 'sampul',
            'format'    => 'raw',
            'value'     => function ($model) {
                if ($model->sampul != '')
                // return '<img src="'.Yii::$app->homeUrl. '../../temp/'.$model->sampul.'" width="100px" height="auto">';
                {
                    return Html::img('@web/temp/' . $model->sampul, ['class' => 'img-responsive', 'style' => 'width:100px; height:100px;']);
                } else {
                    return 'No Image';
                }
                // Ini versi Yii nya yang berfungsi sama dengan yang di atas
            },
        ],

        [
            'attribute' => 'berkas',
            'format'    => 'raw',
            'value'     => function ($model) {
                if ($model->berkas != '') {
                    return '<a class="btn btn-info img-responsive" style="width:100%; height:100px; padding-top:33%;" href="' . Yii::$app->homeUrl . '../../temp/' . $model->berkas . '">Download</a>';
                } else {
                    return 'No File';
                }
            },
        ],

        ['class' => 'yii\grid\ActionColumn'],
    ],
]);?>
</div>
