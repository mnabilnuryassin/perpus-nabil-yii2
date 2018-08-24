<?php

namespace app\controllers;

use app\models\Penulis;
use app\models\PenulisSearch;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * PenulisController implements the CRUD actions for Penulis model.
 */
class PenulisController extends Controller {

    public $layout = 'main-backend';

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Penulis models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel  = new PenulisSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Penulis model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Penulis model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Penulis();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Penulis model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Penulis model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Penulis model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Penulis the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Penulis::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionExportWord() {
        $phpWord = new PhpWord();

        $phpWord->setDefaultFontSize(11); // Font size
        $phpWord->setDefaultFontName('Century Gothic'); // Font family
        $section = $phpWord->addSection([

            // Margin kertas, convert dari cm ke Twip(satuan jarak phpword)
            'marginTop'    => Converter::cmToTwip(1.80),
            'marginBottom' => Converter::cmToTwip(1.30),
            'marginLeft'   => Converter::cmToTwip(1.2),
            'marginRight'  => Converter::cmToTwip(1.6),
        ]);

        // Style didefinisikan oleh variable terlebih dahulu
        $headerStyle = [
            'bold' => true,
        ];
        $paragraphCenter = [
            'alignment' => 'center',
            'spacing'   => 0,
        ];

        $paragraphVertical = [
            'valign' => 'center',
        ];

        // Menambahkan Text beserta dengan stylenya yang sudah di definisikan oleh variable sebelumnya
        $section->addText(
            'JADWAL PENGADAAN LANGSUNG',
            $headerStyle,
            $paragraphCenter
        );
        $section->addText(
            'PENGADAAN JASA KONSULTASI',
            $headerStyle,
            $paragraphCenter
        );

        // Barus baru dengan parameter 1 yang berarti 1 baris
        $section->addTextBreak(1);
        $section->addText(
            'LOREM IPSUM DOLOR SIT AMET',
            [
                'alignment' => 'left',
            ]
        );
        $section->addText(
            'LOREM IPSUM DOLOR SIT',
            [
                'alignment' => 'left',
            ]
        );

        // Barus baru dengan parameter 1 yang berarti 1 baris
        $section->addTextBreak(1);
        $section->addText(
            'LOREM IPSUM DOLOR SIT AMET, CONSECTETUR ADIPISICING ELIT, SED DO EIUSMOD
TEMPOR INCIDIDUNT UT LABORE ET DOLORE MAGNA ALIQUA.',
            $paragraphCenter
        );

        // Barus baru dengan parameter 1 yang berarti 1 baris
        $section->addTextBreak(1);
        $section->addText(
            'Lorem ipsum dolor sit amet',
            [
                'alignment' => 'left',
            ]
        );
        $section->addText(
            'Lorem ipsum dolor sit amet',
            [
                'alignment' => 'left',
            ]
        );

        // Membuat Table dengan align center, warna border 000000(hitam), dan border size 6
        $table = $section->addTable([
            'alignment'  => 'center',
            'bgColor'    => '000000',
            'borderSize' => 6,
        ]);

        // addRow berfungsi seperti <tr> dan addCell berfungsi seperti <td>
        $table->addRow(null);
        $table->addCell(500, $paragraphVertical)->addText('#', $paragraphCenter);
        $table->addCell(500, $paragraphVertical)->addText('ID', $headerStyle, $paragraphCenter);
        $table->addCell(5000, $paragraphVertical)->addText('Nama', $headerStyle, $paragraphCenter);
        $table->addCell(2000, $paragraphVertical)->addText('Alamat', $headerStyle, $paragraphCenter);
        $table->addCell(2000, $paragraphVertical)->addText('Telepon', $headerStyle, $paragraphCenter);
        $table->addCell(2000, $paragraphVertical)->addText('Email', $headerStyle, $paragraphCenter);

        $semuaPenulis = Penulis::find()->all();
        $nomor        = 1;
        // Perulangan ini bertujuan untuk menampilkan secara satu persatu semua buku yang ada
        foreach ($semuaPenulis as $penulis) {
            $table->addRow(null);
            $table->addCell(500, $paragraphVertical)->addText($nomor++, null, $paragraphCenter);
            $table->addCell(500, $paragraphVertical)->addText($penulis->id, null, $paragraphCenter);
            $table->addCell(5000, $paragraphVertical)->addText($penulis->nama, null);
            $table->addCell(2000, $paragraphVertical)->addText($penulis->alamat, null, $paragraphCenter);
            $table->addCell(2000, $paragraphVertical)->addText($penulis->telepon, null, $paragraphCenter);
            $table->addCell(2000, $paragraphVertical)->addText($penulis->email, null, $paragraphCenter);
        }
        $filename  = time() . '_Daftar-Penulis.docx'; // Penamaan dari filenya berikut fungsi time() yang berguna untuk penamaan unik berdasarkan waktu
        $lokasi    = 'exports/' . $filename; // Lokasi penyimpanan File
        $xmlWriter = IOFactory::createWriter($phpWord, 'Word2007'); // Disimpan berdasarkan format 'Word2007'
        $xmlWriter->save($lokasi); // Disimpan didalam lokasi yang telah ditentukan
        return $this->redirect($lokasi); // Redirect menuju halaman ini.
    }
}
