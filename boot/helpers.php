<?php
mb_internal_encoding("UTF-8");

require __DIR__ . '/../vendor/autoload.php';

use \Smalot\PdfParser\Parser;
use PKPass\PKPass;
use Zxing\QrReader;

function createPass($certificateData) {

<<<<<<< HEAD
  
    $pass = new PKPass(env('APPLE_CERTIFICATE'), env('APPLE_PASSWORD'));
=======
    $pass = new PKPass('../Certificates.p12', 'password');
>>>>>>> f7f0602b0ce61f29c3511e14c3dbdb8a7b749684
    
    $desc = $certificateData["type"] === "test" ? "Testnachweis" : "Impfnachweis";
    $headerlabel = $certificateData["type"] === "test" ? "TESTZERTIFIKAT" : "IMPFZERTIFIKAT";
    $passData = [
        'description' => $desc,
        'formatVersion' => 1,
        'organizationName' => '',
        'passTypeIdentifier' => 'pass.com.steinerd.gruenerpass', // Change this!
        'serialNumber' => $certificateData["certificate_data"]["de"]["certificate_identifier"],
        'teamIdentifier' => 'DST3802DPE', // Change this!
        'generic' => [
            'headerFields' => [
                [
                    'key' => 'header', 
                    'label' => $headerlabel,
                    'value' => $certificateData["type"] === "test" ? $certificateData["test_data"]["de"]["test_type"] : $certificateData["vaccination_data"]["de"]["vaccine_prophylaxis"]
                ]
            ],
            'primaryFields' => [
                [
                    'key' => 'vaccineortest',
                    'label' => $certificateData["type"] === "test" ? "TEST TYP" : "IMPFSTOFF",
                    'value' => $certificateData["type"] === "test" ? $certificateData["test_data"]["de"]["test_name_manufacturer"] : $certificateData["vaccination_data"]["de"]["name_manufacturer"]
                ],
            ],
            'secondaryFields' => [
                [
                    'key' => 'name',
                    'label' => 'NAME',
                    'value' => $certificateData["personal_data"]["name"],
                ],
                [
                    'key' => 'dob',
                    'label' => 'GEBURTSDATUM',
                    'value' => $certificateData["personal_data"]["date_of_birth"],
                ],
            ],
            'auxillaryFields' => [
                [
                    'key' => 'field1',
                    'label' => $certificateData["type"] === "test" ? "TESTERGEBNIS": "NUMMER DER IMPFUNG",
                    'value' => $certificateData["type"] === "test" ? $certificateData["test_data"]["de"]["test_result"] : $certificateData["vaccination_data"]["de"]["vaccination_series_number"]
                ],
                [
                    'key' => 'field2',
                    'label' => $certificateData["type"] === "test" ? "TESTDATUM": "IMPFDATUM",
                    'value' => $certificateData["type"] === "test" ? $certificateData["test_data"]["de"]["sample_date_time"] : $certificateData["vaccination_data"]["de"]["date"]
                ]
            ],
            'backFields' => [
                [
                    "key" => "unique_id",
                    "label"=> "Eindeutige Zertifikatkennung",
                    "value"=> $certificateData["certificate_data"]["de"]["certificate_identifier"]
                ],
                [
                    "key" => "info_1",
                    "label"=> "",
                    "value"=> "Dieses Zertifikat ist ausschlie\u00dflich von der adressierten Person f\u00fcr die in den jeweiligen Verordnungen festgelegten Zwecke zu verwenden. Eine missbr\u00e4uchliche Verwendung oder Verf\u00e4lschung des Zertifikats kann strafrechtliche Konsequenzen nach sich ziehen."
                ],
                [
                    "key" => "info_2",
                    "label"=> "",
                    "value"=> "Dieses Zertifikat ist kein Reisedokument. Die wissenschaftlichen Erkenntnisse \u00fcber COVID-19-Impfungen und -Tests sowie \u00fcber die Genesung von einer COVID-19-Infektion entwickeln sich st\u00e4ndig weiter, auch im Hinblick auf neue besorgniserregende Virusvarianten. Bitte informieren Sie sich vor der Reise \u00fcber die am Zielort geltenden Gesundheitsma\u00dfnahmen und damit verbundenen Beschr\u00e4nkungen."
                ],
            ],
            // 'transitType' => 'PKTransitTypeAir',
        ],
        'barcode' => [
            'format' => 'PKBarcodeFormatQR',
            'message' => $certificateData["certificate_data"]["qr"],
            'messageEncoding' => 'iso-8859-1',
        ],
        'backgroundColor' => 'rgb(32,110,247)',
        'logoText' => $desc,
        'relevantDate' => date('Y-m-d\TH:i:sP')
    ];

    $pass->setData($passData);
    $pass->addFile('./images/icon.png');
    $pass->addFile('./images/icon@2x.png');
    $pass->addFile('./images/logo.png');
<<<<<<< HEAD
    $pass->addFile('./images/logo@2x.png');
=======
>>>>>>> f7f0602b0ce61f29c3511e14c3dbdb8a7b749684

    // Create and output the pass
    if(!$pass->create(true)) {
        echo 'Error: ' . $pass->getError();
    }
}

function parseCertificate($inputData) {
    $parser = new Parser();
    $target_file = $inputData["target_file"];
    $upload = $inputData["upload"];
    exec("pdfimages -j ".$target_file." ../zertifikate/".$upload["name"]." -png ", $o);
    $qrImage = "../zertifikate/".$upload["name"]."-003.png";
    unlink("../zertifikate/".$upload["name"]."-000.png");
    unlink("../zertifikate/".$upload["name"]."-001.png");
    unlink("../zertifikate/".$upload["name"]."-002.jpg");
    $pdf = $parser->parseFile($target_file);
    $text = $pdf->getText();
    $lines = explode("\n", $text);       
    $urn = ""; 
    foreach($lines as $line) {
        if (strpos($line, "URN") !== false) {
            $urn = substr($line, strpos($line, "URN"));
        }
    }
    if(strpos($text, "TESTZERTIFIKAT") !== false || strpos($text, "TEST CERTIFICATE")!==false) {
        $isPositive = (strpos($text, "NICHT NACHGEWIESEN") !== false || strpos($text, "NOT DETECTED")!==false) ? false : true;
    
        if(strpos($text, "Ma15 - Gesundheitsdienst Wien") !== false) {
            $data = [
                "type"=>"test",
                "personal_data" => [
                    "name" => $lines[37],
                    "date_of_birth" => $lines[38],
                ],
                "test_data" => [
                    "de" => [
                        "disease_or_agent" => $lines[39],
                        "test_type" => $lines[40],
                        "test_name_manufacturer" => $lines[41],
                        "sample_date_time" => $lines[42],
                        "test_centre" => $lines[43],
                        "test_result" => $isPositive ? "Nachgewiesen" : "Nicht nachgewiesen"
                    ], 
                    "en" => [
                        "disease_or_agent" => $lines[39],
                        "test_type" => $lines[40],
                        "test_name_manufacturer" => $lines[41],
                        "sample_date_time" => $lines[42],
                        "test_centre" => $lines[43],
                        "test_result" => $isPositive ? "detected" : "not detected"
                    ]
                ],
                "certificate_data" => [
                    "de" => [
                        "member_state" => $lines[44],
                        "issuer" => $lines[2]." ".$lines[3],
                        "certificate_identifier" => $urn
                    ], 
                    "en" => [
                        "member_state" => $lines[45],
                        "issuer" => $lines[5]." ".$lines[6],
                        "certificate_identifier" => $urn
                    ]
                ]
            ];

        } else if(strpos($text, "Niederösterreich testet") !== false) {
            $data = [
                "type"=>"test",
                "personal_data" => [
                    "name" => $lines[35],
                    "date_of_birth" => $lines[36],
                ],
                "test_data" => [
                    "de" => [
                        "disease_or_agent" => $lines[37],
                        "test_type" => $lines[38],
                        "test_name_manufacturer" => $lines[39] . " " .$lines[40],
                        "sample_date_time" => $lines[41],
                        "test_centre" => $lines[42],
                        "test_result" => $isPositive ? "Nachgewiesen" : "Nicht nachgewiesen"
                    ], 
                    "en" => [
                        "disease_or_agent" => $lines[37],
                        "test_type" => $lines[38],
                        "test_name_manufacturer" => $lines[39] . " " .$lines[40],
                        "sample_date_time" => $lines[41],
                        "test_centre" => $lines[42],
                        "test_result" => $isPositive ? "detected" : "not detected"
                    ]
                ],
                "certificate_data" => [
                    "de" => [
                        "member_state" => $lines[43],
                        "issuer" => $lines[2]." ".$lines[3],
                        "certificate_identifier" => $urn
                    ], 
                    "en" => [
                        "member_state" => $lines[44],
                        "issuer" => $lines[5]." ".$lines[6],
                        "certificate_identifier" => $urn
                    ]
                ]
            ];

        } else if (strpos($text, "Lifebrain Rotes Kreuz") !== false || strpos($text, "Rotes Kreuz Lifebrain COVID Labor GmbH")) {
            $data = [
                "type"=>"test",
                "personal_data" => [
                    "name" => $lines[35],
                    "date_of_birth" => $lines[36],
                ],
                "test_data" => [
                    "de" => [
                        "disease_or_agent" => $lines[40],
                        "test_type" => $lines[38],
                        "test_name_manufacturer" => $lines[39],
                        "sample_date_time" => $lines[40],
                        "test_centre" => $lines[41],
                        "test_result" => $isPositive ? "Nachgewiesen" : "Nicht nachgewiesen"
                    ], 
                    "en" => [
                        "disease_or_agent" => $lines[40],
                        "test_type" => $lines[38],
                        "test_name_manufacturer" => $lines[39],
                        "sample_date_time" => $lines[40],
                        "test_centre" => $lines[41],
                        "test_result" => $isPositive ? "detected" : "not detected"
                    ]
                ],
                "certificate_data" => [
                    "de" => [
                        "member_state" => $lines[42],
                        "issuer" => $lines[2]." ".$lines[3],
                        "certificate_identifier" => $urn
                    ], 
                    "en" => [
                        "member_state" => $lines[43],
                        "issuer" => $lines[5]." ".$lines[6],
                        "certificate_identifier" => $urn
                    ]
                ]
            ];
        } else {
            echo "Sorry, ich habe noch kein entsprechendes Testzertifikat erhalten und kann somit nicht sicherstellen, dass die Daten richtig geparst werden. Wenn du mich bei der Entwicklung unterstützen möchtest, kannst du mir gerne deinen (abgelaufenen) Testnachweis schicken und ich werde die Logik entsprechend implementieren.";
            unlink($target_file);
        }
    } else if(strpos($text, "IMPFZERTIFIKAT") !== false || strpos($text, "VACCINATION CERTIFICATE")!==false) {
        $data = [
            "type"=>"vaccination",
            "personal_data" => [
                "name" => $lines[38],
                "date_of_birth" => $lines[39],
            ],
            "vaccination_data" => [
                "de" => [
                    "disease_or_agent" => $lines[40],
                    "vaccine_prophylaxis" => $lines[41],
                    "name_product" => $lines[43]." ".$lines[44],
                    "name_manufacturer" => $lines[45],
                    "vaccination_series_number" => $lines[46],
                    "date" => $lines[47],
                ], 
                "en" => [
                    "disease_or_agent" => $lines[40],
                    "vaccine_prophylaxis" => $lines[42],
                    "name_product" => $lines[43]." ".$lines[44],
                    "name_manufacturer" => $lines[45],
                    "vaccination_series_number" => $lines[46],
                    "date" => $lines[47],
                ]
            ],
            "certificate_data" => [
                "de" => [
                    "member_state" => $lines[48],
                    "issuer" => $lines[2]." ".$lines[3],
                    "certificate_identifier" => $urn
                ], 
                "en" => [
                    "member_state" => $lines[49],
                    "issuer" => $lines[5]." ".$lines[6],
                    "certificate_identifier" => $urn
                ]
            ]
        ];
    }

    $data["certificate_data"]["qr"] = readQRData($qrImage);

    return $data;
}

function readQRData($file) {
    try{
        $qrcode = new QrReader($file);
        $text = $qrcode->text();
        if($text) {
            return $text;
        }
    }catch(TypeError $te){
    
    }    
}