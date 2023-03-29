<?php
class Scores extends Controller
{
  // Properties, field
  private $scoreModel;

  // Dit is de constructor
  public function __construct()
  {
    $this->scoreModel = $this->model('Score');
  }

  public function index()
  {

    $scores = $this->scoreModel->getScores();

    /**
     * Maak de inhoud voor de tbody in de view
     */
    $rows = '';
    foreach ($scores as $value) {
      $rows .= "<tr>
                  <td>$value->Id</td>
                  <td>$value->name</td>
                  <td>$value->capitalCity</td>
                  <td>$value->continent</td>
                  <td>$value->population</td>
                  <td><a href='" . URLROOT . "/countries/delete/$value->Id'>delete</a></td>
                </tr>";
    }


    $data = [
      'title' => '<h1>overzicht</h1>',
      'countries' => $rows
    ];
    $this->view('scores/index', $data);
  }

  public function update($id = null)
  {
    // var_dump($id);exit();
    // var_dump($_SERVER);exit();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $this->scoreModel->updateScore($_POST);
      header("Location:" . URLROOT . "/scores/index");
    } else {
      $row = $this->scoreModel->getSingleScore($id);
      $data = [
        'title' => '<h1>Update scores</h1>',
        'row' => $row
      ];
      $this->view("scores/update", $data);
    }
  }

  public function delete($id)
  {
    $this->countryModel->deleteCountry($id);

    $data = [
      'deleteStatus' => "Het record met id = $id is verwijdert"
    ];
    $this->view("countries/delete", $data);
    header("Refresh:3; url=" . URLROOT . "/countries/index");
  }

  public function create()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // var_dump($_POST);
      try {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $this->countryModel->createCountry($_POST);
        header("Location:" . URLROOT . "/countries/index");
      } catch (PDOException $e) {
        echo "Het inserten van het record is niet gelukt";
        header("Refresh:3; url=" . URLROOT . "/countries/index");
      }
    } else {
      $data = [
        'title' => "Voeg een nieuw land in"
      ];

      $this->view("countries/create", $data);
    }
  }

  public function scanCountry()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

      $record = $this->countryModel->getSingleCountryByName($_POST["country"]);

      $rowData = "<tr>
                    <td>$record->id</td>
                    <td>$record->name</td>
                    <td>$record->capitalCity</td>
                    <td>$record->continent</td>
                    <td>$record->population</td>
                  </tr>";

      $data = [
        'title' => 'Dit is het gescande land',
        'rowData' => $rowData
      ];

      $this->view('countries/scanCountry', $data);
    } else {
      $data = [
        'title' => 'Scan het land',
        'rowData' => ""
      ];

      $this->view('countries/scanCountry', $data);
    }
  }
}
