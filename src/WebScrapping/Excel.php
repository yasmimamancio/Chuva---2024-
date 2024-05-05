<?php

namespace Chuva\Php\WebScrapping;

use OpenSpout\Common\Entity\Cell;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Writer;

class Excel
{
  private $data;
  public function __construct($data = [])
  {
    $this->data = $data;
  }
  public function createExcel()
  {
    $writer = new Writer();
    $writer->openToFile(__DIR__ . "/../../file/teste.xlsx");
    $style = new Style();
    $style->setFontBold();
    $header = [
      Cell::fromValue('ID', $style),
      Cell::fromValue('Title', $style),
      Cell::fromValue('Type', $style),
    ];

    $writer->addRow(new Row($header));
    foreach ($this->data as $row) {
      $row_array = [
        Cell::fromValue($row->id),
        Cell::fromValue($row->title),
        Cell::fromValue($row->type),
      ];
      $writer->addRow(new Row($row_array));
    }
    $writer->close();
    /**
     * in case of streaming data directly to the browser with $writer->openToBrowser() ensure
     * to not send any further data after the $writer->close() call as that would be appended
     * to the generated file and that makes Excel complain about it being corrupted.
     * For example, you could place an `exit;` here or terminate the output in any other way.
     */
  }
}
