<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Penerbit */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Penerbits', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="penerbit-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nama',
            'alamat:ntext',
            'telepon',
            'email:email',
            [
                'label' => 'Jumlah Buku',
                'value' =>  $model->getJumlahBuku(),
            ],
        ],
    ]) ?>

</div>

    <h1>Daftar Buku</h1>
<p>
    <?= Html::a('Tambah Buku', ['buku/create', 'id_penerbit' => $model->id], ['class' => 'btn btn-primary']) ?>
</p>
<table class="table">
    <tr>
        <th>No</th>
        <td>Nama Buku</td>           
    </tr>
    <?php 
        $no=1;
        foreach ($model->findAllBuku() as $buku): ?>
        <tr>
            <td><?= $no; ?></td>
            <td><?= Html::a($buku->nama, ['buku/view','id' => $buku->id]); ?></td>
        </tr>
    <?php 
        $no++;
        endforeach 
    ?>
</table>