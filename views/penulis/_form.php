<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

require_once '../vendor/fzaninotto/Faker/src/autoload.php';
$faker = Faker\Factory::create('uk_UK');

/* @var $this yii\web\View */
/* @var $model app\models\Penulis */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="penulis-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nama')->textInput(['maxlength' => true, 'value' => $faker->name]) ?>

    <?= $form->field($model, 'alamat')->textarea(['value' => $faker->address, 'rows' => 6]) ?>

    <?= $form->field($model, 'telepon')->textInput(['value' => $faker->phoneNumber, 'maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['value' => $faker->freeEmail, 'maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
