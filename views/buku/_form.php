<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\models\Penulis;
use app\models\Penerbit;
use app\models\Kategori;
use kartik\file\FileInput;

require_once '../vendor/fzaninotto/Faker/src/autoload.php';
$faker = Faker\Factory::create('id_ID');

/* @var $this yii\web\View */
/* @var $model app\models\Buku */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="buku-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nama')->textInput(['value' => $faker->realText($nbWords = 17), 'maxlength' => true]) ?>

    <?= $form->field($model, 'tahun_terbit')->textInput(['maxlength' => true, 'value' => $faker->year($max = 'now')]) ?>

    <?= $form->field($model, 'id_penulis')->widget(Select2::classname(), [
        'data'          => Penulis::getList(),
        'options'       => ['placeholder' => 'Pilih penulis ...'],
        'pluginOptions' => [
        'allowClear'    => true
    ],
    ]); ?>

    <?= $form->field($model, 'id_penerbit')->widget(Select2::classname(), [
        'data'          => Penerbit::getList(),
        'options'       => ['placeholder' => 'Pilih penerbit ...'],
        'pluginOptions' => [
        'allowClear'    => true
    ],
    ]); ?>

    <?= $form->field($model, 'id_kategori')->widget(Select2::classname(), [
        'data'          => Kategori::getList(),
        'options'       => ['placeholder' => 'Pilih kategori ...'],
        'pluginOptions' => [
        'allowClear'    => true
    ],
    ]); ?>

    <?= $form->field($model, 'sinopsis')->textarea(['value' => $faker->text($maxNbChars=700), 'rows' => 6]) ?>

    <?= $form->field($model, 'sampul')->widget(FileInput::classname(), [
        'options' => ['accept' => ''],
        'pluginOptions'=>['allowedFileExtensions'=>['jpg','gif','png','jpeg'],'showUpload' => false], 
        ]);
    ?>

    <?= $form->field($model, 'berkas')->widget(FileInput::classname(), [
        'options' => ['accept' => ''],
        'pluginOptions'=>['allowedFileExtensions'=>['pdf', 'docx', 'doc'],'showUpload' => false], 
        ]); 
    ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
