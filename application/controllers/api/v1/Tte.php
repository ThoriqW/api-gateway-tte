<?php
defined('BASEPATH') or exit('No direct script access allowed');
include_once 'General.php';

require 'vendor/autoload.php';
require 'phpqrcode/qrlib.php';

use chriskacerguis\RestServer\RestController;

class Tte extends General
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('Tte_model');
    }

    function user_status_get($nik)
    {
        $tteRest = json_decode(tteCekUser($nik), true);

        if ($tteRest['status_code'] == 1111) {
            $response = [
                "metadata" => [
                    "code" => $tteRest['status_code'],
                    "status" => $tteRest['status']
                ],
                "response" => [
                    "message" => $tteRest['message']
                ]
            ];
            $status = RestController::HTTP_OK;
        } else if ($tteRest['status_code'] == 1110) {
            $response = [
                "metadata" => [
                    "code" => $tteRest['status_code'],
                    "status" => $tteRest['status']
                ],
                "response" => [
                    "message" => $tteRest['message']
                ]
            ];
            $status = RestController::HTTP_OK;
        } else if ($tteRest['status_code'] == 1006) {
            $response = [
                "metadata" => [
                    "code" => $tteRest['status_code'],
                    "status" => $tteRest['status']
                ],
                "response" => [
                    "message" => $tteRest['message']
                ]
            ];
            $status = RestController::HTTP_OK;
        } else if ($tteRest['status_code'] == 2021) {
            $response = [
                "metadata" => [
                    "code" => $tteRest['status_code'],
                    "status" => $tteRest['status']
                ],
                "response" => [
                    "message" => $tteRest['message']
                ]
            ];
            $status = RestController::HTTP_OK;
        } else {
            $response = [
                "metadata" => [
                    "code" => $tteRest['status_code'],
                    "status" => explode(":", $tteRest['error'])[1]
                ],
                "response" => [
                    "message" => explode(":", $tteRest['error'])[0]
                ]
            ];
            $status = RestController::HTTP_BAD_REQUEST;
        }
        $this->set_response($response, $status);
    }
    function signlog_post()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $startDate = $data['start_date'];
        $lastDate = $data['last_date'];
        $dataLog = $this->Tte_model->getSelectLog($startDate, $lastDate);
        if ($dataLog->num_rows() > 0) {
            foreach ($dataLog->result_array() as $_dataLog) {
                $listLog[] = [
                    "nik" => $_dataLog['no_ktp'],
                    "tanggal" => $_dataLog['tanggal'],
                    "no_rawat" => $_dataLog['no_rawat'],
                    "kode" => $_dataLog['kode_berkas'],
                    "nama_berkas" => $_dataLog['nama_berkas'],
                    "status_code" => $_dataLog['status_code'],
                    "lokasi_file" => $_dataLog['lokasi_file'],
                    "status" => $_dataLog['status']
                ];
            }
            $response = [
                "metadata" => [
                    "code" => 200,
                    "status" => "Suksess"
                ],
                "response" => [
                    "list" => $listLog
                ]
            ];
            $status = RestController::HTTP_OK;
        } else {
            $response = [
                "metadata" => [
                    "code" => 201,
                    "status" => "Failed"
                ],
                "response" => [
                    "message" => "Failed"
                ]
            ];
            $status = RestController::HTTP_CREATED;
        }
        $this->set_response($response, $status);
    }
    function getAkunTTE_post()
    {
        $dataLog = $this->Tte_model->getData("akun_tte");
        if ($dataLog->num_rows() > 0) {
            foreach ($dataLog->result_array() as $_dataLog) {
                $listLog[] = [
                    "name" => $_dataLog['name'],
                    "nik" => $_dataLog['nik'],
                    "sign_image" => $_dataLog['sign_image'],
                ];
            }
            $response = [
                "metadata" => [
                    "code" => 200,
                    "status" => "Suksess"
                ],
                "response" => [
                    "list" => $listLog
                ]
            ];
            $status = RestController::HTTP_OK;
        } else {
            $response = [
                "metadata" => [
                    "code" => 201,
                    "status" => "Failed"
                ],
                "response" => [
                    "message" => "Failed"
                ]
            ];
            $status = RestController::HTTP_CREATED;
        }
        $this->set_response($response, $status);
    }
    function postAkunTTE_post()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
    
            if (!$data || !isset($data['name'], $data['nik'], $data['sign_image'])) {
                throw new Exception('Invalid input data');
            }

            $existingData = $this->Tte_model->getAkunTTEbyNik($data['nik']);

            if ($existingData->num_rows() > 0) {
                throw new Exception('Akun dengan nik ini sudah ada');
            }
    
            $in['name'] = $data['name'];
            $in['nik'] = $data['nik'];
            $in['sign_image'] = $data['sign_image'] . ".png";
    
            $this->Tte_model->saveData("akun_tte", $in);
    
            echo json_encode([
                'metadata' => [
                    'code' => 200,
                    'message' => 'Data saved successfully'
                ],
                'response' => $in
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'metadata' => [
                    'code' => 400,
                    'message' => $e->getMessage()
                ]
            ]);
        }
    }
    function updateAkunTTE_post()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
    
            if (!$data || !isset($data['name'], $data['nik'], $data['sign_image'])) {
                throw new Exception('Invalid input data');
            }
    
            $in['name'] = $data['name'];
            $in['nik'] = $data['nik'];
            $in['sign_image'] = $data['sign_image'] . ".png";
    
            $this->Tte_model->updateData("akun_tte", $in);
    
            echo json_encode([
                'metadata' => [
                    'code' => 200,
                    'message' => 'Data saved successfully'
                ],
                'response' => $in
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'metadata' => [
                    'code' => 400,
                    'message' => $e->getMessage()
                ]
            ]);
        }
    }
    function deleteAkunTTE_post()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
    
            $nik = $data['nik'];
    
            $this->Tte_model->deleteTTE($nik);
            
            echo json_encode([
                'metadata' => [
                    'code' => 200,
                    'message' => 'Data delete successfully'
                ],
                'response' => $nik
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'metadata' => [
                    'code' => 400,
                    'message' => $e->getMessage()
                ]
            ]);
        }
    }
    function signfile_post()
    {
        $data = $this->input->post();
        if (empty($data['nik'])) {
        } else if (empty($data['passphrase'])) {
        } else if (empty($data['location'])) {
        } else if (empty($data['image'])) {
        } else if (empty($data['tag'])) {
        } else {


            $tempimageTTD = $_FILES['imageTTD']['tmp_name'];
            $tempFile = $_FILES['file']['tmp_name'];
            $fileName = $_FILES['file']['name'];
            $fileType = $_FILES['file']['type'];
            $fileSize = $_FILES['file']['size'];
            $NameimageTTE = $this->Tte_model->getDataImageTTE($data['nik'])->row()->sign_image;
            file_put_contents("tag", $data['tag']);
            $imageTTE = $_SERVER['DOCUMENT_ROOT'] . "/api-gateway/resources/image_tte/" . $NameimageTTE;
            $response = tteSign($data['nik'], $data['passphrase'], $tempFile, $data['tag'], $data['image'], $imageTTE);
            switch ($data['applications']) {
                case "simrs":
                    $fp = FCPATH . "/resources/path_simrs/" . $data['location'] . "/" . $fileName;
                    file_put_contents($fp, $response);
                    break;
            }

            if (json_decode($response, true)['metadata']['code'] == 400) {
                $in['no_ktp'] = $data['nik'];
                $in['tanggal'] = date("Y-m-d H:i:s");
                $in['status'] = "Gagal";
                $in['status_code'] = "400 [" . json_decode($response, true)['response'] . "]";
                $in['lokasi_file'] = $data['location'] . "/" . $fileName;
                $in['nama_berkas'] = $fileName;
                $in['applications'] = $data['applications'];
                $this->Tte_model->saveData('log_berkas_tte', $in);
            } else {
                $in['no_ktp'] = $data['nik'];
                $in['tanggal'] = date("Y-m-d H:i:s");
                $in['status'] = "Sukses";
                $in['status_code'] = 200;
                $in['lokasi_file'] = $data['location'] . "/" . $fileName;
                $in['nama_berkas'] = $fileName;
                $in['applications'] = $data['applications'];
                $this->Tte_model->saveData('log_berkas_tte', $in);
            }

            echo $response;
        }
    }
    private function generateQRCodeImage($text, $width, $filePath, $logoPath) {
        // Check if the required libraries are available
        if (!class_exists('QRcode')) {
            throw new Exception('QRcode class not found. Please include the required library.');
        }

        // Validate inputs
        if (empty($text) || empty($width) || empty($filePath) || empty($logoPath)) {
            throw new InvalidArgumentException('Invalid input data. All parameters are required.');
        }

        // Generate QR code
        $tempDir = sys_get_temp_dir();
        $qrTempPath = $tempDir . '/qr_temp.png';

        // Try to create the QR code, catch errors if any
        try {
            QRcode::png($text, $qrTempPath, QR_ECLEVEL_H, 10);
        } catch (Exception $e) {
            throw new Exception('Failed to generate QR code: ' . $e->getMessage());
        }

        // Load QR code and logo images
        if (!file_exists($qrTempPath)) {
            throw new Exception('QR code file not created.');
        }
        $qrImage = imagecreatefrompng($qrTempPath);
        if (!file_exists($logoPath)) {
            throw new Exception('Logo file not found.');
        }
        $logoImage = imagecreatefromstring(file_get_contents($logoPath));

        // Resize the logo
        $logoWidth = imagesx($logoImage);
        $logoHeight = imagesy($logoImage);
        $newLogoWidth = (int) ($width / 3);
        $newLogoHeight = (int) ($logoHeight * ($newLogoWidth / $logoWidth));

        imagecolortransparent($logoImage , imagecolorallocatealpha($logoImage , 0, 0, 0, 127));
        imagealphablending($logoImage , false);
        imagesavealpha($logoImage , true);
        imagecopyresampled($logoImage, $logoImage, 0, 0, 0, 0, $newLogoWidth, $newLogoHeight, $logoWidth, $logoHeight);

        // Calculate coordinates to place the logo at the center of the QR code
        $qrWidth = imagesx($qrImage);                                                                                                                                                                                                                                                                                                                                                                                               
        $qrHeight = imagesy($qrImage);
        $logoX = ($qrWidth - $newLogoWidth) / 2;
        $logoY = ($qrHeight - $newLogoHeight) / 2;

        // Merge the logo onto the QR code
        imagecopy($qrImage, $logoImage, $logoX, $logoY, 0, 0, $newLogoWidth, $newLogoHeight);

        // Save the final image to a file
        if (!imagepng($qrImage, $filePath)) {
            throw new Exception('Failed to save the QR code image.');
        }

        // Free up memory
        imagedestroy($qrImage);
        imagedestroy($logoImage);

        // Delete temporary QR code image
        if (!unlink($qrTempPath)) {
            throw new Exception('Failed to delete the temporary QR code image.');
        }
    }
    function signfilewithimage_post()
    {
        $data = $this->input->post();
        $tempFile = $_FILES['file']['tmp_name'];
        $tempimageTTD = $_FILES['imageTTD']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileType = $_FILES['file']['type'];
        $fileSize = $_FILES['file']['size'];
        echo tteSignV1($data['nik'], $data['passphrase'], $tempFile, $data['image']);
        //echo tteSignV1($data['nik'],  $data['passphrase'], $tempFile, $data['image'],$data['tampilan'], $tempimageTTD);
    }
    function signfileV1_post()
    {
        $data = $this->input->post();
        $tempFile = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileType = $_FILES['file']['type'];
        $fileSize = $_FILES['file']['size'];
        $imageTTE = null;
        if($data['tampilan'] == 'visible'){
            // if($this->Tte_model->getDataImageTTE($data['nik']) === null){
            //     echo json_encode([
            //         'metadata' => [
            //             'code' => 400,
            //             'message' => "Nik belum terdaftar di simrs"
            //         ]
            //     ]);
            //     $fps = $_SERVER['DOCUMENT_ROOT'] . "/webapps/" . 'errornik.log';
            //     file_put_contents($fps, "nik belum terdaftar di smrs");
            //     return;
            // }
            $fps = $_SERVER['DOCUMENT_ROOT'] . "/webapps/" . 'errornik.log';
            file_put_contents($fps, $this->Tte_model->getDataImageTTE($data['nik']));
            $NameimageTTE = $this->Tte_model->getDataImageTTE($data['nik'])->row()->sign_image;
            $text = "https://qrcodette.rssindhutrisnopalu.com/home/" . $data['location'] . "/" . $data['id'] . "/" . $fileName;
            $filePath = $_SERVER['DOCUMENT_ROOT'] . "/api-gateway/resources/image_tte/" . $NameimageTTE;
            $logoPath = $_SERVER['DOCUMENT_ROOT'] . "/api-gateway/assets/logo_qrcode.png";
            try {
                $this->generateQRCodeImage($text, "300", $filePath, $logoPath);
            } catch (Exception $e) {
                echo json_encode([
                    'metadata' => [
                        'code' => 400,
                        'message' => $e->getMessage()
                    ]
                ]);
                file_put_contents($filePath, $e);
                return;
            }
            $imageTTE = $_SERVER['DOCUMENT_ROOT'] . "/api-gateway/resources/image_tte/" . $NameimageTTE;
        }
        $response = tteSignV1($data['nik'], $data['passphrase'], $tempFile, $data['image'], $data['tampilan'], $data['xAxis'], $data['yAxis'], $data['height'], $data['width'], $data['page'], $data['tag'], $imageTTE);
        $fps = $_SERVER['DOCUMENT_ROOT'] . "/webapps/" . 'statuscode.log';
        $statuscode = http_response_code();
        file_put_contents($fps, $statuscode);
        if (json_decode($response, true) === null) {
            $in['no_ktp'] = $data['nik'];
            $in['tanggal'] = date("Y-m-d H:i:s");
            $in['status'] = "Sukses";
            $in['status_code'] = 200;
            $in['lokasi_file'] = $fileName;
            $in['nama_berkas'] = $fileName;
            $this->Tte_model->saveData('log_berkas_tte', $in);
        } else {
            if (json_decode($response, true)['metadata']['code'] === 400) {
                $in['no_ktp'] = $data['nik'];
                $in['tanggal'] = date("Y-m-d H:i:s");
                $in['status'] = "Gagal";
                $in['status_code'] = "400 [" . json_decode($response, true)['response'] . "]";
                $in['lokasi_file'] = $fileName;
                $in['nama_berkas'] = $fileName;
                $this->Tte_model->saveData('log_berkas_tte', $in);
            } else if (json_decode($response, true)['metadata']['code'] === 2011) {
                $in['no_ktp'] = $data['nik'];
                $in['tanggal'] = date("Y-m-d H:i:s");
                $in['status'] = "Gagal";
                $in['status_code'] = "2011 [" . json_decode($response, true)['response'] . "]";
                $in['lokasi_file'] = $fileName;
                $in['nama_berkas'] = $fileName;
                $this->Tte_model->saveData('log_berkas_tte', $in);
            } else if (json_decode($response, true)['metadata']['code'] === 2031) {
                $in['no_ktp'] = $data['nik'];
                $in['tanggal'] = date("Y-m-d H:i:s");
                $in['status'] = "Gagal";
                $in['status_code'] = "2031 [" . json_decode($response, true)['response'] . "]";
                $in['lokasi_file'] = $fileName;
                $in['nama_berkas'] = $fileName;
                $this->Tte_model->saveData('log_berkas_tte', $in);
            } else {
                $in['no_ktp'] = $data['nik'];
                $in['tanggal'] = date("Y-m-d H:i:s");
                $in['status'] = "Gagal";
                $in['status_code'] = 2021;
                $in['lokasi_file'] = $fileName;
                $in['nama_berkas'] = $fileName;
                $this->Tte_model->saveData('log_berkas_tte', $in);
            }
        }

        echo $response;
    }
}