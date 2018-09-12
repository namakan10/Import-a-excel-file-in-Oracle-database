<?php
    require 'vendor/autoload.php';

    use Box\Spout\Writer\WriterFactory;
    use Box\Spout\Reader\ReaderFactory;
    use Box\Spout\Common\Type;

    /*
     * Recuperation du numéro du mois
     */
    if ($_POST['month'] == 'Janvier'){
        $numMonth = 1;
    }
    elseif ($_POST['month'] == 'Fevrier'){
        $numMonth = 2;
    }
    elseif ($_POST['month'] == 'Mars'){
        $numMonth = 3;
    }
    elseif ($_POST['month'] == 'Avril'){
        $numMonth = 4;
    }
    elseif ($_POST['month'] == 'Mai'){
        $numMonth = 5;
    }
    elseif ($_POST['month'] == 'Juin'){
        $numMonth = 6;
    }
    elseif ($_POST['month'] == 'Juillet'){
        $numMonth = 7;
    }
    elseif ($_POST['month'] == 'Aôut'){
        $numMonth = 8;
    }
    elseif ($_POST['month'] == 'Septembre'){
        $numMonth = 9;
    }
    elseif ($_POST['month'] == 'Octobre'){
        $numMonth = 10;
    }
    elseif ($_POST['month'] == 'Novembre'){
        $numMonth = 11;
    }
    elseif ($_POST['month'] == 'Decembre'){
        $numMonth = 12;
    }

    $writer = WriterFactory::create(Type::CSV);
    $reader = ReaderFactory::create(Type::XLSX);
    $reader->setShouldFormatDates(true);

    $writer->openToFile('public/temp/temp.csv');
    $reader->open($file);


    foreach ($reader->getSheetIterator() as $sheet) {
        if ($sheet->getName() === 'Saisie Données Journalières') {
            foreach ($sheet->getRowIterator() as $row) {


                if($row[0] != 'No. Item' /*On Ignore la première ligne*/){

                    /*
                     * Recuperation du mois de la ligne en cours et du jours
                     */
                    $time = strtotime($row[1]);
                    $dt = date('d/m/y',$time);
                    $month  = intval(DateTime::createFromFormat('d/m/y', $dt)->format('m'));
                    $days = intval(DateTime::createFromFormat('d/m/y', $dt)->format('d'));


                    if($month == $numMonth OR $month == $numMonth+1){

                        if($month == $numMonth+1){
                            if ($days <= 6){
                                if($row[7] != '' OR $row[8] != ''){
                                    $writer->addRow($row);
                                 }
                            }
                        }
                        else{
                            if($row[7] != '' OR $row[8] != '') {

                                $writer->addRow($row);
                            }
                        }
                    }
                }
            }
        }
    }

    $reader->close();
    $writer->close();




