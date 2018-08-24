<?php

namespace app\controllers;

use app\models\Buku;
use app\models\BukuSearch;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * BukuController implements the CRUD actions for Buku model.
 */
class BukuController extends Controller {

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
     * Lists all Buku models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel  = new BukuSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Buku model.
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
     * Creates a new Buku model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_kategori = null, $id_penulis = null, $id_penerbit = null) {
        $model = new Buku();

        $model->id_kategori = $id_kategori;
        $model->id_penulis  = $id_penulis;
        $model->id_penerbit = $id_penerbit;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //mengambil berkas dan sampul dari form
            $sampul = UploadedFile::getInstance($model, 'sampul');
            $berkas = UploadedFile::getinstance($model, 'berkas');

            // if ($sampul == null && $berkas != null ) {
            //     # code...
            // }

            //merubah nama berkas dan sampul ditambah dengan waktu, supaya unik
            $model->sampul = time() . '_' . $sampul->name;
            $model->berkas = time() . '_' . $berkas->name;

            // save ke database
            $model->save();

            // menyimpan file hasil upload ke dalam direktori komputer
            $sampul->saveAs(Yii::$app->basePath . '/web/temp/' . $model->sampul);
            $berkas->saveAs(Yii::$app->basePath . '/web/temp/' . $model->berkas);

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Buku model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        // Mengambil data lama di databases
        $sampul_lama = $model->sampul;
        $berkas_lama = $model->berkas;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // Mengambil data baru di layout _form
            $sampul = UploadedFile::getInstance($model, 'sampul');
            $berkas = UploadedFile::getInstance($model, 'berkas');

            //jika ada data file yang diubah maka data lama akan di hapus dan diganti dengan data baru yang sudah diambil, jika tidak ada data yang diubah maka file akan langsung save data-data yang lama
            if ($sampul !== null) {
                unlink(Yii::$app->basePath . '/web/temp/' . $sampul_lama);
                $model->sampul = time() . '_' . $sampul->name;
                $sampul->saveAs(Yii::$app->basePath . '/web/temp/' . $model->sampul);
            } else {
                $model->sampul = $sampul_lama;
            }

            if ($berkas !== null) {
                unlink(Yii::$app->basePath . '/web/temp/' . $berkas_lama);
                $model->berkas = time() . '_' . $berkas->name;
                $berkas->saveAs(Yii::$app->basePath . '/web/temp/' . $model->berkas);
            } else {
                $model->berkas = $berkas_lama;
            }

            // Simpan data ke Database
            $model->save(false);
            // Menuju ke view id dari data yang sudah dibuat.
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Buku model.
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
     * Finds the Buku model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Buku the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Buku::findOne($id)) !== null) {
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
        $paragraphStyleAlignCenter = [
            'alignment'   => 'center',
            'spacing'     => 0,
            'spaceAfter'  => 10,
            'spaceBefore' => 0,
        ];

        $paragraphVerticalAlign = [
            'valign' => 'center',
        ];

        //
        //
        //
        //
        //
        // Menambahkan Text beserta dengan stylenya yang sudah di definisikan oleh variable sebelumnya
        $section->addText(
            'JADWAL PENGADAAN LANGSUNG',
            $headerStyle,
            $paragraphStyleAlignCenter
        );
        $section->addText(
            'PENGADAAN JASA KONSULTASI',
            $headerStyle,
            $paragraphStyleAlignCenter
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
            $paragraphStyleAlignCenter
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
        $table->addCell(500, $paragraphVerticalAlign)->addText('#', $paragraphStyleAlignCenter);
        $table->addCell(500, $paragraphVerticalAlign)->addText('ID', $headerStyle, $paragraphStyleAlignCenter);
        $table->addCell(5000, $paragraphVerticalAlign)->addText('Kegiatan', $headerStyle, $paragraphStyleAlignCenter);
        $table->addCell(2000, $paragraphVerticalAlign)->addText('Tahun Terbit', $headerStyle, $paragraphStyleAlignCenter);
        $table->addCell(2000, $paragraphVerticalAlign)->addText('Penulis', $headerStyle, $paragraphStyleAlignCenter);
        $table->addCell(2000, $paragraphVerticalAlign)->addText('Penerbit', $headerStyle, $paragraphStyleAlignCenter);
        $table->addCell(2000, $paragraphVerticalAlign)->addText('Kategori', $headerStyle, $paragraphStyleAlignCenter);
        $table->addCell(null, $paragraphVerticalAlign)->addText('Sampul', $headerStyle, $paragraphStyleAlignCenter);
        $semuaBuku = Buku::find()->all();
        $nomor     = 1;
        // Perulangan ini bertujuan untuk menampilkan secara satu persatu semua buku yang ada
        foreach ($semuaBuku as $buku) {
            $table->addRow(null);
            $table->addCell(500, $paragraphVerticalAlign)->addText($nomor++, null, $paragraphStyleAlignCenter);
            $table->addCell(500, $paragraphVerticalAlign)->addText($buku->id, null, $paragraphStyleAlignCenter);
            $table->addCell(5000, $paragraphVerticalAlign)->addText($buku->nama, null, $paragraphStyleAlignCenter);
            $table->addCell(2000, $paragraphVerticalAlign)->addText($buku->tahun_terbit, null, $paragraphStyleAlignCenter);
            $table->addCell(2000, $paragraphVerticalAlign)->addText($buku->getPenulis(), null, $paragraphStyleAlignCenter);
            $table->addCell(2000, $paragraphVerticalAlign)->addText($buku->getPenerbit(), null, $paragraphStyleAlignCenter);
            $table->addCell(2000, $paragraphVerticalAlign)->addText($buku->getKategori(), null, $paragraphStyleAlignCenter);
            $table->addCell(null, $paragraphStyleAlignCenter)->addImage('temp/' . $buku->sampul,
                [
                    'height'    => 50,
                    'width'     => 50,
                    'alignment' => 'center',
                    'valign'    => 'center',
                ]); // addImage berfungsi untuk menampilkan gambar pada saat export ke word.
        }
        $filename  = time() . '_Table-Buku.docx'; // Penamaan dari filenya berikut fungsi time() yang berguna untuk penamaan unik berdasarkan waktu
        $lokasi    = 'exports/' . $filename; // Lokasi penyimpanan File
        $xmlWriter = IOFactory::createWriter($phpWord, 'Word2007'); // Disimpan berdasarkan format 'Word2007'
        $xmlWriter->save($lokasi); // Disimpan didalam lokasi yang telah ditentukan
        return $this->redirect($lokasi); // Redirect menuju halaman ini.
    }

    public function actionExportBeritaAcara() {

        // Membuat model baru
        $phpWord = new PhpWord();

        // Membuat default ukuran fontz
        $phpWord->setDefaultFontSize(10);

        // Membuat default fontz
        $phpWord->setDefaultFontName('Footlight MT Light');

        // Membuat Jarak kertasnya
        $section = $phpWord->addSection([
            'marginTop'    => Converter::cmToTwip(0.9),
            'marginBottom' => Converter::cmToTwip(1),
            'marginLeft'   => Converter::cmToTwip(2.5),
            'marginRight'  => Converter::cmToTwip(2.5),
        ]);

        // Custom Style
        // Define styles
        $fontStyleName = 'myOwnStyle';
        $phpWord->addFontStyle($fontStyleName, ['color' => 'FF0000']);
        $paragraphStyleName = 'P-Style';
        $phpWord->addParagraphStyle($paragraphStyleName, ['spaceAfter' => 95]);
        $multilevelNumberingStyleName = 'multilevel';
        $phpWord->addNumberingStyle(
            $multilevelNumberingStyleName,
            [
                'type'   => 'multilevel',
                'levels' => [
                    [
                        'format' => 'upperRoman',
                        'text'   => '%1.',
                        'left'   => 0,
                    ],
                    [
                        'format'  => 'decimal',
                        'text'    => '%2.',
                        'left'    => 920,
                        'hanging' => 200,
                    ],
                    [
                        'format'  => 'lowerLetter',
                        'text'    => '%3.',
                        'left'    => 1150,
                        'hanging' => 200,
                    ],
                ],
            ]
        );

        $fontStyleSizeBold = [
            'bold' => true,
            'size' => 11,
        ];

        $fontStyleSizeUnderBold = [
            'underline' => 'single',
            'bold'      => true,
            'size'      => 11,
        ];

        $fontStyleSize = [
            'size' => 11,
        ];

        $fontStyleSizeUnder = [
            'size'      => 11,
            'underline' => 'single',
        ];

        $paragraphStyleAlignCenter = [
            'alignment'   => 'center',
            'spaceAfter'  => 0,
            'spaceBefore' => 0,
        ];

        $paragraphStyleNoSpace = [
            'spaceAfter'  => 0,
            'spaceBefore' => 0,
        ];

        $paragraphStyleMarginLeft = [
            'indentation' => [
                'left' => 920,
            ],
        ];

        $paragraphStyleVertHoriSpace = [
            'spaceAfter'  => 0,
            'spaceBefore' => 0,
            'indentation' => [
                'left' => 920,
            ],
        ];

        $paragraphStyleVertHoriSpace1 = [
            'spaceAfter'  => Converter::cmToTwip(0.4),
            'spaceBefore' => Converter::cmToTwip(0),
            'indentation' => [
                'left' => 920,
            ],
        ];

        $paragraphStyleVertHoriSpace2 = [
            'spaceAfter'  => Converter::cmToTwip(0.4),
            'spaceBefore' => Converter::cmToTwip(0.2),
            'indentation' => [
                'left' => 700,
            ],
        ];

        $paragraphStyleVertHoriSpace3 = [
            'spaceAfter'  => Converter::cmToTwip(0),
            'spaceBefore' => Converter::cmToTwip(1),
            'indentation' => [
                'left' => 2000,
            ],
        ];

        $paragraphStyleVertHoriSpace4 = [
            'indentation' => [
                'left' => 1700,
            ],
        ];

        // Mulai

        // Images
        $section->addImage('../uploads/logo-mbn.png', ['width' => 85, 'height' => 67, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

        // Label atas, tengah
        $section->addText(
            'LEMBAGA ADMINISTRASI NEGARA',
            $fontStyleSizeBold,
            $paragraphStyleAlignCenter
        );

        $section->addText(
            'REPUBLIK INDONESIA',
            $fontStyleSizeBold,
            [
                'alignment'   => 'center',
                'spaceAfter'  => Converter::cmToTwip(0.4),
                'spaceBefore' => Converter::cmToTwip(0),
            ]
        );

        $section->addText(
            'BERITA ACARA KLARIFIKASI DAN NEGOSIASI',
            $fontStyleSizeUnderBold,
            $paragraphStyleAlignCenter,
            $paragraphStyleNoSpace
        );

        $section->addText(
            'Nomor : 157/PP/PBJ 01.2/450417',
            $fontStyleSizeBold,
            $paragraphStyleAlignCenter
        );

        $section->addText(
            'Tanggal : ' . date('d F Y'),
            $fontStyleSizeBold,
            [
                'alignment'   => 'center',
                'spaceAfter'  => Converter::cmToTwip(0.4),
                'spaceBefore' => Converter::cmToTwip(0),
            ]
        );

        $section->addText(
            'Pada hari ini Jum’at tanggal Delapan belas bulan Mei tahun Dua ribu delapan belas (' . date('d-m-Y') . ') dimulai  pada  pukul 14.00 WIB, bertempat di Ruang Rapat Layanan Pengadaan Barang/Jasa Kantor LAN Pusat Jakarta, Jl.Veteran No. 10 Jakarta, telah diadakan Rapat Klarifikasi dan Negosiasi terhadap Dokumen Penawaran untuk Pekerjaan Pembangunan Sistem Informasi Pengadaan (SIP) Kantor LAN Jakarta Jl. Veteran No. 10, Jakarta Pusat.',
            $fontStyleSize,
            [
                'alignment'   => 'both',
                'spaceAfter'  => Converter::cmToTwip(0.4),
                'spaceBefore' => Converter::cmToTwip(0),
            ]

        );
        // Lists
        $section->addListItem('Hadir dalam rapat :', 0, $fontStyleSize, $multilevelNumberingStyleName, $paragraphStyleNoSpace);

        $section->addListItem('Pejabat Pengadaan Barang/Jasa Satker 450417 LAN Jakarta', 1, $fontStyleSize, $multilevelNumberingStyleName, $paragraphStyleNoSpace);
        $section->addText('Dwi Astuti, ST', $fontStyleSize, $paragraphStyleVertHoriSpace);

        $section->addListItem('Penyedia:', 1, $fontStyleSize, $multilevelNumberingStyleName, $paragraphStyleNoSpace);
        $section->addText('Konsultan Perorangan', $fontStyleSize, $paragraphStyleVertHoriSpace);
        $section->addText('Diwakili oleh : Sdr. Thomas Alfa Edison', $fontStyleSize, $paragraphStyleVertHoriSpace1);

        $section->addListItem('Berdasarkan klarifikasi dan negosiasi teknis dan harga, dihasilkan hal-hal sebagai berikut:', 0, $fontStyleSize, $multilevelNumberingStyleName, $paragraphStyleNoSpace);
        $section->addListItem('Dokumen Penawaran Teknis :', 1, $fontStyleSize, $multilevelNumberingStyleName);
        $section->addText('Penyedia sanggup untuk melaksanakan pekerjaan sesuai dengan spesifikasi teknis sebagaimana tercantum dalam dokumen pengadaan;', $fontStyleSize, $paragraphStyleMarginLeft);
        $section->addListItem('Dokumen Penawaran Harga:', 1, $fontStyleSize, $multilevelNumberingStyleName);
        //
        //
        //
        $section->addListItem('Kewajaran biaya pada Rincian Biaya Langsung Personil (remuneration);', 2, $fontStyleSize, $multilevelNumberingStyleName);
        $section->addListItem('Kewajaran Biaya tenaga ahli;', 2, $fontStyleSize, $multilevelNumberingStyleName);
        $section->addListItem('Kewajaran biaya pada Rincian Biaya Langsung Non-Personil (direct reimbursable cost)', 2, $fontStyleSize, $multilevelNumberingStyleName);

        $listItemRun = $section->addListItemRun(2, $multilevelNumberingStyleName);
        $textrun     = $listItemRun->addTextRun();
        $textrun->addText('Disepakati bahwa harga penawaran terkoreksi yang diajukan sebesar Rp. 11.000.000,- (Sebelas juta rupiah) dinegosiasi menjadi                 ', $fontStyleSize);

        $textrun->addText('Rp. 10.000.000,- (Sepuluh juta rupiah) dapat diterima.', ['bold' => true, 'size' => 11]);

        $section->addListItem('Rapat ditutup pukul 15.00 WIB.', 0, $fontStyleSize, $multilevelNumberingStyleName, $paragraphStyleNoSpace);
        $section->addText('Demikian Berita Acara ini dibuat dalam rangkap secukupnya untuk dipergunakan seperlunya.', $fontStyleSize, $paragraphStyleVertHoriSpace2);
        //
        // Tanda Tangan menggunakan Table
        //
        $section->addText('Penyedia Jasa', $fontStyleSize, $paragraphStyleVertHoriSpace3);
        $section->addTextBreak(3);
        $section->addText('Thomas Alfa Edison', $fontStyleSizeUnder, $paragraphStyleVertHoriSpace4);
        $section->addText('Konsultan Perorangan', $fontStyleSize, $paragraphStyleVertHoriSpace4);

        // Tempat penyimpanan file sama nama file.
        $filename = time() . '_' . 'Berita-Acara.docx';
        $path     = 'exports/' . $filename;
        $xmlWrite = IOFactory::createWriter($phpWord, 'Word2007');
        $xmlWrite->save($path);

        return $this->redirect($path);
    }

    public function actionExportExcel() {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $filename    = time() . '_Excel.xlsx'; // Penamaan dari filenya berikut fungsi time() yang berguna untuk penamaan unik berdasarkan waktu
        $path        = 'exports/' . $filename; // Lokasi penyimpanan File

        $sheet->setCellValue('A1', 'No.');
        $sheet->setCellValue('B1', 'ID');
        $sheet->setCellValue('C1', 'Nama');
        $sheet->setCellValue('D1', 'Tahun Terbit');
        $semuaBuku = Buku::find()->all();
        $nomor     = 1;
        $row1      = 2;
        $row2      = $row1;
        $row3      = $row2;
        $row4      = $row3;

        foreach ($semuaBuku as $buku) {
            $sheet->setCellValue('A' . $row1++, $nomor++);
            $sheet->setCellValue('B' . $row2++, $buku->id);
            $sheet->setCellValue('C' . $row3++, $buku->nama);
            $sheet->setCellValue('D' . $row4++, $buku->tahun_terbit);
        }

        $spreadsheet->getActiveSheet()
            ->getStyle('A1:D' . $row4)
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx'); // Disimpan berdasarkan format 'Xlxs'
        $writer = new Xlsx($spreadsheet);
        $writer->save($path); // Disimpan didalam lokasi yang telah ditentukan
        return $this->redirect($path); // Redirect menuju halaman ini.
    }

    public function actionExportMpdf() {
        $mpdf     = new \Mpdf\Mpdf();
        $filename = time() . '_Mpdf.pdf';
        $path     = 'exports/' . $filename; // Lokasi penyimpanan File

        $mpdf->WriteHTML($this->renderPartial('mpdf_template', ['model' => $model]));

        $mpdf->Output($path);
        return $this->redirect($path); // Redirect menuju halaman buku/index.
    }
}
