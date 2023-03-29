<?php
  class InstructeurModel {
    // Properties, fields
    private $db;
    public $helper;

    public function __construct() {
      $this->db = new Database();
    }


    public function getInstructeurs() {
      $sql = "SELECT  Instructeur1.Voornaam
                     ,Instructeur1.Tussenvoegsel
                     ,Instructeur1.Achternaam
                     ,Instructeur1.Mobiel
                     ,Instructeur1.DatumInDienst
                     ,Instructeur1.AantalSterren
                      ,Instructeur1.Id as Instructeur1Id

                    
              FROM `Instructeur1` 
              ORDER BY AantalSterren DESC;";
      $this->db->query($sql);
      $result = $this->db->resultSet();
      return $result;
    }

    public function getInstructeurById($Instructeur1Id) 
    {
       $sql = "SELECT   INST.Id AS Instructeur1Id
                       ,INST.Voornaam
                       ,INST.Tussenvoegsel
                       ,INST.Achternaam
                       ,INST.DatumInDienst
                       ,INST.AantalSterren
                        
                FROM  	Instructeur1 AS INST
                WHERE 	INST.Id = :Id";
        
        $this->db->query($sql);
        $this->db->bind(':Id', $Instructeur1Id, PDO::PARAM_INT);
        $result = $this->db->single();
        return $result;
    }

    public function getGebruikteVoertuigen($Instructeur1Id) 
    {
      
        $sql = "SELECT   TypeVoertuig.TypeVoertuig
                        ,Typevoertuig.Rijbewijscategorie
                        ,Voertuig.Type
                        ,Voertuig.Kenteken
                        ,Voertuig.Bouwjaar
                        ,Voertuig.Brandstof
                        ,instructeur1.Id AS Instructeur1Id
                        

                FROM    Instructeur1
                INNER JOIN VoertuigInstructeur
                ON         VoertuigInstructeur.Instructeur1Id = Instructeur1.Id
                INNER JOIN Voertuig
                ON         VoertuigInstructeur.VoertuigId = Voertuig.Id
                INNER JOIN TypeVoertuig
                ON         Voertuig.TypeVoertuigId = TypeVoertuig.Id
                WHERE   Instructeur1.Id = :Id
                ORDER BY TypeVoertuig.Rijbewijscategorie ASC";
        $this->db->query($sql);
        $this->db->bind(':Id', $Instructeur1Id, PDO::PARAM_INT);
        $result = $this->db->resultSet();
        return $result;
    }

    public function allebeschibareVoertuigen()
    {
      $sql = " SELECT 
                voertuig.Id AS VoertuigId,
                  typevoertuig.TypeVoertuig,
                 Voertuig.Type,
                 Voertuig.Kenteken,
                 Voertuig.Bouwjaar,
                 instructeur1.Id AS Instructeur1Id,
                voertuig.Brandstof,
                typevoertuig.Rijbewijscategorie
            
            FROM Voertuig				
            LEFT JOIN  	voertuiginstructeur
             ON	VoertuigInstructeur.VoertuigId = Voertuig.Id  
            LEFT JOIN 	typevoertuig 
            ON 	typeVoertuig.Id = Voertuig.typeVoertuigId 
            LEFT JOIN 	instructeur1 
            ON 	instructeur1.Id = voertuiginstructeur.Instructeur1Id
                   
           WHERE 		voertuiginstructeur.VoertuigId is null";
                $this->db->query($sql);
                
                $result = $this->db->resultSet();
                return $result;
    }

    public function updategebruikteauto($voertuigId, $InstructeurId)
    {

      $sql = "INSERT INTO VoertuigInstructeur 
                          (
                             VoertuigId
                            ,Instructeur1Id
                            ,DatumToekenning
                          ) 
                  VALUES(:voertuigId, :instructeur1Id, :datumToekenning)";

    
      $this->db->query($sql);
      $this->db->bind(':voertuigId', $voertuigId, PDO::PARAM_INT);
      $this->db->bind(':instructeur1Id', $InstructeurId, PDO::PARAM_INT);
      $this->db->bind(':datumToekenning', "2023-03-23", PDO::PARAM_STR);
      $result = $this->db->execute();
      return $result;
    }

   
  }