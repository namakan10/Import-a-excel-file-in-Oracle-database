<?php
    require 'vendor/autoload.php';

    use Box\Spout\Writer\WriterFactory;
    use Box\Spout\Reader\ReaderFactory;
    use Box\Spout\Common\Type;


    $writer = WriterFactory::create(Type::CSV);
    $reader = ReaderFactory::create(Type::XLSX);
    $reader->setShouldFormatDates(true);

    $writer->openToFile('temp/temp0.csv');
    $reader->open($file);


    foreach ($reader->getSheetIterator() as $sheet) {
        if ($sheet->getName() === 'Saisie Données Journalières') {
            foreach ($sheet->getRowIterator() as $row) {
                $writer->addRow($row);
            }
        }
    }
    $reader->close();
    $writer->close();


