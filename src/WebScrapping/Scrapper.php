<?php

namespace Chuva\Php\WebScrapping;

use Chuva\Php\WebScrapping\Entity\Paper;
use Chuva\Php\WebScrapping\Entity\Person;

/**
 * Does the scrapping of a webpage.
 */
class Scrapper
{
  /**
   * Loads paper information from the HTML and returns the array with the data.
   */
  public function scrap(\DOMDocument $dom): array
  {
    // Array to store the extracted data
    $data = [];

    // Create DOMXPath instance to query the HTML document
    $html = new \DomXPath($dom);

    // Query all elements with class 'paper-card'
    $cards = $html->query("//*[contains(@class, 'paper-card')]");

    // Loop through each paper card
    foreach ($cards as $card) {
      // Extract title of the paper
      $titles = $card->getElementsByTagName('h4');
      $title = $titles->item(0)->textContent;

      // Initialize variables for type and ID
      $type = '';
      $id = '';

      // Loop through div elements inside the card
      $divs = $card->getElementsByTagName('div');
      foreach ($divs as $element) {
        // Check if the element has the class 'tags mr-sm' for type
        if ($element->getAttribute('class') === 'tags mr-sm') {
          $type = $element->textContent;
          continue;
        }
        // Check if the element has the class 'volume-info' for ID
        if ($element->getAttribute('class') === 'volume-info') {
          $id = $element->textContent;
          continue;
        }
      }

      // Extract authors
      $authors = $card->getElementsByTagName('div');
      $authorData = [];
      foreach ($authors as $element) {
        if ($element->getAttribute('class') === 'authors') {
          $authorSpans = $element->getElementsByTagName('span');
          foreach ($authorSpans as $span) {
            // Extract author name
            $author = str_replace(";", "", $span->textContent);
            $authorData[] = ["name" => $author];
          }
        }
      }

      // Create Paper object and add it to the data array
      $data[] = new Paper($id, $title, $type, $authorData);
    }

    // Return the extracted data
    return $data;
  }
}

  // return [ 
  //   new Paper(
  //     123,
  //     'The Nobel Prize in Physiology or Medicine 2023',
  //     'Nobel Prize',
  //     [
  //       new Person('Katalin Karikó', 'Szeged University'),
  //       new Person('Drew Weissman', 'University of Pennsylvania'),
  //     ]
  //   ),
  // ];
