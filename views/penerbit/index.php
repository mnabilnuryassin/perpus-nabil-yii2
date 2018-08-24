<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PenerbitSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = 'Penerbits';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="penerbit-index">

    <h1><?=Html::encode($this->title)?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?=Html::a('Create Penerbit', ['create'], ['class' => 'btn btn-success'])?>
        <?=Html::a('Export Word', ['penerbit/export-word'], ['class' => 'btn btn-info btn-flat']);?>
    </p>

    <?=GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns'      => [
        ['class' => 'yii\grid\SerialColumn'],

        'id',
        'nama',
        'alamat:ntext',
        'telepon',
        'email:email',
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
