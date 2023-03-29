<?php
class Instructeur extends Controller
{
  // Properties, field
  private $instructeurModel;

  // Dit is de constructor
  public function __construct()
  {
    $this->instructeurModel = $this->model('InstructeurModel');
  }

  public function index()
  {
    /**
     * Haal alle instructeurs op uit de model
     */
    $instructeurs = $this->instructeurModel->getInstructeurs();

    /**
     * Maak tabelrijen van de opgehaalde data over de instructeurs
     */
    $rows = '';
    foreach ($instructeurs as $value) {
      $rows .= "<tr>
                  <td>$value->Voornaam</td>
                  <td>$value->Tussenvoegsel</td>
                  <td>$value->Achternaam</td>
                  <td>$value->Mobiel</td>
                  <td>$value->DatumInDienst</td>
                  <td>$value->AantalSterren</td>
                  <td><a href='" . URLROOT . "/instructeur/gebruiktevoertuigen/$value->Instructeur1Id'><i class='bi bi-car-front-fill'></i></a></td>
                </tr>";
    }

    /**
     * Stuur de informatie door naar de view
     */
    $data = [
      'title' => 'Instructeurs in dienst',
      'amountOfInstructeurs' => sizeof($instructeurs),
      'rows' => $rows
    ];
    $this->view('/instructeur/index', $data);
  }




  public function gebruikteVoertuigen($Instructeur1Id)
  {
    // var_dump($Instructeur1Id);

    $instructeur = $this->instructeurModel->getInstructeurById($Instructeur1Id);
    // var_dump($Instructeur1Id);

    $gebruikteVoertuigen = $this->instructeurModel->getGebruikteVoertuigen($Instructeur1Id);

    // var_dump($Instructeur1Id);
    // exit();

    if (sizeOf($gebruikteVoertuigen) == 0) 
    {
      $rows = "<tr><td colspan='6'>Er zijn op dit moment nog geen voertuigen toegewezen aan deze instructeur</td></tr>";
      header('Refresh:3; url=' . URLROOT . '/instructeur/index');
    } 
    else 
    {
      $rows = '';
      foreach ($gebruikteVoertuigen as $value) 
      {
        $rows .= "<tr>
                    <td>$value->TypeVoertuig</td>
                    <td>$value->Type</td>
                    <td>$value->Kenteken</td>
                    <td>$value->Bouwjaar</td>
                    <td>$value->Brandstof</td>
                    <td>$value->Rijbewijscategorie</td>
                  </tr>";
      }
    }


    $data = [
      'title' => 'Door Instructeur gebruikte voertuigen',
      'voornaam' => $instructeur->Voornaam,
      'tussenvoegsel' => $instructeur->Tussenvoegsel,
      'achternaam' => $instructeur->Achternaam,
      'datumInDienst' => $instructeur->DatumInDienst,
      'aantalSterren' => $instructeur->AantalSterren,
      'rows' => $rows,
      'Instructeur1Id' => $Instructeur1Id
    ];

    $this->view('/instructeur/gebruikteVoertuigen', $data);
  }





  
  public function beschikbareauto()
  {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') 
    {

      $_post = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $InstructeurId = $_POST['Instructeur1Id'];
      
      $instructeur = $this->instructeurModel->getInstructeurById($InstructeurId);

      $allebeschibareVoertuigen = $this->instructeurModel->allebeschibareVoertuigen();

      if ($allebeschibareVoertuigen == 0) 
      {
        $rows = "<tr><td colspan='6'>Er zijn op dit moment nog geen voertuigen toegewezen aan deze instructeur</td></tr>";
        header('Refresh:3; url=' . URLROOT . '/instructeur/index');
      } 
      else 
      {


        $rows = '';
        foreach ($allebeschibareVoertuigen as $value) 
        {
          $voertuigId = $value->VoertuigId;
            $rows .= 
            "<tr>
              <td>$value->TypeVoertuig</td>
              <td>$value->Type</td>
              <td>$value->Kenteken</td>
              <td>$value->Bouwjaar</td>
              <td>$value->Brandstof</td>
              <td>$value->Rijbewijscategorie</td>
              <td><a href='" . URLROOT . "/instructeur/updategebruiktevoertuig/" . $voertuigId . "/" . $InstructeurId . "'><i class='bi bi-car-front-fill'></i></a></td>
            </tr>";
        }
      }
    }


    $data = [
              'title' => 'alle beschibare voertuigen',
              'voornaam' => $instructeur->Voornaam,
              'tussenvoegsel' => $instructeur->Tussenvoegsel,
              'achternaam' => $instructeur->Achternaam,
              'datumInDienst' => $instructeur->DatumInDienst,
              'aantalSterren' => $instructeur->AantalSterren,
              'rows' => $rows,

            ];
    $this->view('/instructeur/beschikbareauto', $data);
  }


  
  public function updategebruiktevoertuig($voertuigId, $InstructeurId)
  {
     $update = $this->instructeurModel->updategebruikteauto($voertuigId, $InstructeurId);
      
      $this->view('/instructeur/updategebruiktevoertuig',);
  }
}

