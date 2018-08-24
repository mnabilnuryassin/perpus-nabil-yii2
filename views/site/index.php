<?php

use app\models\Buku;
use app\models\Kategori;
use app\models\Penerbit;
use app\models\Penulis;
use miloschuman\highcharts\Highcharts;

/* @var $this yii\web\View */

$this->title = 'Perpustakaan yii';
?>
<div class="site-index">


  <div class="row top_tiles">
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
      <div class="tile-stats">
        <div class="icon"><i class="fa fa-book"></i></div>
        <div class="count"><?=Yii::$app->formatter->asInteger(Buku::getCount());?></div>
        <h3>Jumlah Buku</h3>
        <p>Lorem ipsum psdea itgum rixt.</p>
      </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
      <div class="tile-stats">
        <div class="icon"><i class="fa fa-pencil-square-o"></i></div>
        <div class="count"><?=Yii::$app->formatter->asInteger(Penulis::getCount());?></div>
        <h3>Jumlah Penulis</h3>
        <p>Lorem ipsum psdea itgum rixt.</p>
      </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
      <div class="tile-stats">
        <div class="icon"><i class="glyphicon glyphicon-print"></i></div>
        <div class="count"><?=Yii::$app->formatter->asInteger(Penerbit::getCount());?></div>
        <h3>Jumlah Penerbit</h3>
        <p>Lorem ipsum psdea itgum rixt.</p>
      </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
      <div class="tile-stats">
        <div class="icon"><i class="fa fa-tachometer"></i></div>
        <div class="count"><?=Yii::$app->formatter->asInteger(Kategori::getCount());?></div>
        <h3>Jumlah Kategori</h3>
        <p>Lorem ipsum psdea itgum rixt.</p>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-sm-6">
      <div class="x_panel">
        <div class="x_title">
          <h3 class="box-title">Buku Berdasarkan Penulis</h3>
        </div>
        <div class="box-body">
          <?=Highcharts::widget([
            'options' => [
              'credits'     => false,
              'title'       => ['text' => 'PENULIS BUKU'],
              'exporting'   => ['enabled' => true],
              'plotOptions' => [
                'pie' => [
                  'cursor' => 'pointer',
                ],
              ],
              'series'      => [
                [
                  'type' => 'pie',
                  'name' => 'Penulis',
                  'data' => Penulis::getGrafikList(),
                ],
              ],
            ],
          ]);?>
        </div>
      </div>
    </div>


    <div class="col-sm-6">
      <div class="x_panel">
        <div class="x_title">
          <h3 class="box-title">Buku Berdasarkan Penerbit</h3>
        </div>
        <div class="box-body">
          <?=Highcharts::widget([
            'options' => [
              'credits'     => false,
              'title'       => ['text' => 'PENERBIT BUKU'],
              'exporting'   => ['enabled' => true],
              'plotOptions' => [
                'pie' => [
                  'cursor' => 'pointer',
                ],
              ],
              'series'      => [
                [
                  'type' => 'pie',
                  'name' => 'Penerbit',
                  'data' => Penerbit::getGrafikList(),
                ],
              ],
            ],
          ]);?>
        </div>
      </div>
    </div>

    <div class="col-sm-6">
      <div class="x_panel">
        <div class="x_title">
          <h3 class="box-title">Buku Berdasarkan Kategori</h3>
        </div>
        <div class="box-body">

          <?=Highcharts::widget([
            'options' => [
              'credits'     => false,
              'title'       => ['text' => 'KATEGORI BUKU'],
              'exporting'   => ['enabled' => true],
              'plotOptions' => [
                'pie' => [
                  'cursor' => 'pointer',
                ],
              ],
              'series'      => [
                [
                  'type' => 'pie',
                  'name' => 'Kategori',
                  'data' => Kategori::getGrafikList(),
                ],
              ],
            ],
          ]);?>
        </div>
      </div>
    </div>
  </div>





  <?php
  $listbuku = Buku::findAll(['77', '78']);

  foreach ($listbuku as $buku) {
    ?> <br> <?php echo $buku->nama;
    echo $buku->tahun_terbit;
  }
  ?>
</div>
