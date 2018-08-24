<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">



    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="../vendors/animate.css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
</head>

<body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
          <section class="login_content">
            <form>
                <?php
                $this->title                   = 'Login Form';
                $this->params['breadcrumbs'][] = $this->title;
                ?>


                <div class="site-login">
                    <h1><?=Html::encode($this->title)?></h1>

                    <?php $form = ActiveForm::begin([
                        'id'          => 'login-form',
                        'layout'      => 'horizontal',
                        'fieldConfig' => [
                            'template'     => "{label}\n<div class=\"col-lg-12\">{input}</div>\n<div class=\"col-lg-12\">{error}</div>",
                            'labelOptions' => ['class' => 'col-lg-1 control-label'],
                        ],
                    ]);?>
                    <div>
                        <?=$form->field($model, 'username')->textInput(['autofocus' => true, 'class' => 'form-control', 'placeholder' => 'Username'])?>
                    </div>
                    <div>
                        <?=$form->field($model, 'password')->passwordInput(['class' => 'form-control', 'placeholder' => 'Username'])?>
                    </div>
                    <div>
                        <?=$form->field($model, 'rememberMe')->checkbox([
                            'template' => "<div class=\"col-lg-3\">{input} {label}</div>\n<div class=\"col-lg-6\">{error}</div>",
                        ])?>
                    </div>
                    <div>
                        <div class="form-group">
                            <div class="col-lg-1">
                                <?=Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button'])?>
                            </div>
                        </div>
                        <a class="reset_pass col-lg-12" href="#">Lost your password?</a>
                    </div>
                    <?php ActiveForm::end();?>
                    <div class="clearfix"></div>

                    <div class="separator">
                        <p class="change_link">New to site?
                          <a href="#signup" class="to_register"> Create Account </a>
                      </p>

                      <div class="clearfix"></div>
                      <br />

                      <div>
                          <h1>PERPUSTAKAAN</h1>                         
                      </div>
                  </div>
              </form>
          </section>
      </div>
  </div>
</div>
</body>
</html>
