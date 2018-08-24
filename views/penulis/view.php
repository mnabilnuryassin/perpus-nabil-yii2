<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Penulis */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Penulis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="penulis-view">

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
                'label' => 'Jumlah buku',
                'value' => $model->getJumlahBuku(),
            ],
        ],
    ]) ?>

</div>

<h1>Daftar Buku</h1>
<p>
    <?= Html::a('Tambah Buku', ['buku/create', 'id_penulis' => $model->id], ['class' => 'btn btn-primary']) ?>
</p>
<div>
    <table class="table">
        <tr>
            <th>No</th>
            <th>Nama Buku</th>
            <th>&nbsp;</th>
        </tr>

        <?php $no=1; foreach ($model->findAllBuku() as $buku): ?>
            <tr>
                <td><?= $no; ?></td>
                <td><?= Html::a($buku->nama, ['buku/view', 'id' => $buku->id]) ?></td>
                <td>
                    <?= Html::a("Sunting", ["buku/update","id"=>$buku->id]); ?> &nbsp;
                    <?= Html::a("Hapus", ["buku/delete","id"=>$buku->id],['data-method' => 'post', 'data-confirm' => 'apa anda yakin ingin menghapus data ini?']); ?> &nbsp;
                </td>
            </tr>            
        <?php $no++; endforeach ?>
    </table>
</div>