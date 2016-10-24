<?php
/**
 * Created by PhpStorm.
 * User: carlosagudobelloso
 * Date: 24/10/16
 * Time: 6:21
 */

namespace AppBundle\Services;

use Liuggio\ExcelBundle\Factory as ExcelFactory;
use PHPExcel;

class ExcelExport
{
    /** @var PHPExcel */
    private $phpExcel;

    /**
     * ExcelExport constructor.
     * @param ExcelFactory $excelFactory
     */
    public function __construct(ExcelFactory $excelFactory) {
        $this->phpExcel = $excelFactory->createPHPExcelObject();
    }

    /**
     * @param $messages
     * @return \PHPExcel
     */
    public function generateExcelObjectFromArray($messages, $columns, $properties = null)
    {
        $this->generateExcelProperties($properties);
        $columnLetters = $this->columnsLetters();
        $i = 1;

        $this->phpExcel->setActiveSheetIndex(0);
        $j = 0;
        foreach($columns as $column) {
            $this->phpExcel->setActiveSheetIndex(0)->setCellValue($columnLetters[$j] . $i, $column);
            $j++;
        }

        $i = 2;
        foreach ($messages as $result) {
            $j = 0;
            foreach($columns as $column) {
                $this->phpExcel->setActiveSheetIndex(0)->setCellValue($columnLetters[$j] . $i, $result[$j]);
                $j++;
            }
            $i++;
        }

        $this->phpExcel->getActiveSheet()->setTitle('Simple');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $this->phpExcel->setActiveSheetIndex(0);

        return $this->phpExcel;
    }

    private function generateExcelProperties($properties = null)
    {
        if (!$properties) {
            $properties = [];
        }
        $properties = array_merge($properties, [
            'creator' => "Carlos Agudo",
            'title' => 'Images Messages Export',
            'subject' => 'Export',
            'description' => ' Image Messages Exportation',
            'keywords'  => 'office 2005 openxml php',
            'category' => 'Test Result File',
        ]);

        $this->phpExcel->getProperties()->setCreator($properties['creator'])
            ->setLastModifiedBy($properties['creator'])
            ->setTitle($properties['title'])
            ->setSubject($properties['subject'])
            ->setDescription($properties['description'])
            ->setKeywords($properties['keywords'])
            ->setCategory($properties['category']);

        return $this->phpExcel;
    }

    /**
     * @Todo generate bigger ranges
     * @return array
     */
    private function columnsLetters() {
        return range('A','Z');
    }
}