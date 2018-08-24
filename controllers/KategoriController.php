<?php

namespace app\controllers;

use app\models\Kategori;
use app\models\KategoriSearch;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * KategoriController implements the CRUD actions for Kategori model.
 */
class KategoriController extends Controller {

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
     * Lists all Kategori models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel  = new KategoriSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Kategori model.
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
     * Creates a new Kategori model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Kategori();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Kategori model.
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
     * Deletes an existing Kategori model.
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
     * Finds the Kategori model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Kategori the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Kategori::findOne($id)) !== null) {
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
            'alignment'   => 'center',
            'spacing'     => 0,
            'spaceAfter'  => 200,
            'spaceBefore' => 200,
        ];

        $paragraphVertical = [
            'valign' => 'center',
        ];

        $title = [
            'size' => 200,
        ];

        // Menambahkan Text beserta dengan stylenya yang sudah di definisikan oleh variable sebelumnya
        $section->addText(
            'KATEGORI',
            $headerStyle,
            $paragraphCenter,
            $title
        );

        // Baris baru dengan parameter 1 yang berarti 1 baris
        $section->addTextBreak(1);

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

        $semuaKategori = Kategori::find()->all();
        $nomor         = 1;
        // Perulangan ini bertujuan untuk menampilkan secara satu persatu semua buku yang ada
        foreach ($semuaKategori as $kategori) {
            $table->addRow(null);
            $table->addCell(500, $paragraphVertical)->addText($nomor++, null, $paragraphCenter);
            $table->addCell(500, $paragraphVertical)->addText($kategori->id, null, $paragraphCenter);
            $table->addCell(5000, $paragraphVertical)->addText($kategori->nama, null, $paragraphCenter);
        }
        $filename  = time() . '_Daftar-Kategori.docx'; // Penamaan dari filenya berikut fungsi time() yang berguna untuk penamaan unik berdasarkan waktu
        $lokasi    = 'exports/' . $filename; // Lokasi penyimpanan File
        $xmlWriter = IOFactory::createWriter($phpWord, 'Word2007'); // Disimpan berdasarkan format 'Word2007'
        $xmlWriter->save($lokasi); // Disimpan didalam lokasi yang telah ditentukan
        return $this->redirect($lokasi); // Redirect menuju halaman ini.
    }
}
