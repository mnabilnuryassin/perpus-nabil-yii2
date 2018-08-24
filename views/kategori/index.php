<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\KategoriSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = 'Kategoris';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kategori-index">

    <h1><?=Html::encode($this->title)?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?=Html::a('Create Kategori', ['create'], ['class' => 'btn btn-success'])?>
        <?=Html::a('Export Word', ['kategori/export-word'], ['class' => 'btn btn-info btn-flat']);?>
    </p>

    <?=GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns'      => [
        ['class' => 'yii\grid\SerialColumn'],

        'id',
        'nama',
        [
            'label' => 'Jumlah Buku',
            'value' => function ($model) {
                return $model->getJumlahBuku();

            },

        ],

        ['class' => 'yii\grid\ActionColumn'],
    ],
]);?>
</div>
