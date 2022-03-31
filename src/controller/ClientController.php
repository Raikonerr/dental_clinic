<?php

require_once 'src/security/jwt.php';

class ClientController extends JwtController{
    public function __construct(){

    }

    public function index(){
        echo 'l index';
    }

    public function signin(){
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            require_once('src/model/authentication.php');
            require_once('src/config/Header.php');
            if(isset($_POST['email'])){
                $auth = new Authentication();
                $result = $auth->signin($_POST['email']);
                if($result){
                    echo json_encode($result);
                }else{
                    echo Authentication::message('l`utilisateur n`existe pas');

                }
            }else{
                    echo Authentication::message('le champ est vide', true);
                }
            }
        }

        public function signup(){
        
                    if($_SERVER['REQUEST_METHOD']=='POST')
                    {
                       require_once('src/model/authentication.php');
                       require_once('src/config/Header.php');
                       if(isset($_POST['email']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['age']) && isset($_POST['date_de_naissance'])){
                           if(!empty($_POST['email']) && !empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['age']) && !empty($_POST['date_de_naissance'])){
                               $auth = new Authentication();
                               $res = $auth->signup($_POST['email'], $_POST['nom'], $_POST['prenom'], $_POST['age'], $_POST['date_de_naissance']);
                               if($res){
                                   echo Authentication::message('l`utilisateur etait ajouté avec succes!', false); 
                               }else{
                                   echo Authentication::message('l`utilisateur existe deja!', false);
                               }

                           }else{
                            echo Authentication::message('le champ est vide', true);                                 
                           }
                       }else{
                        echo Authentication::message('Erreur 4xx', true);
                       }
                    }else{
                        echo Authentication::message('l`erreur 5xx', true);
                    }
         
        }




        public function addRdv(){
            require_once('src/config/header.php');
            require_once('src/model/connection.php');

            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                    if(!empty($_POST['idC']) && !empty($_POST['sujet_rdv']) && !empty($_POST['creneau_start'])&& !empty($_POST['creneau_end']) ){
                        $db = new Database();
                        $res = $db->insert('rdv' , ['sujet_rdv','creneau_start','creneau_end','idC'],[$_POST['sujet_rdv'],$_POST['creneau_start'],$_POST['creneau_end'],$_POST['idC']]);
                        if($res){
                            echo Database::message('Merci pour votre confiance', false);
                        }else {
                            echo Database::message('Echouer!', true);
                        }
                    }else{
                        echo Database::message('Vueillez remplir tout le form', true);
                    }
            }else{
                echo Database::message('probleme du serveur , desole pour les inconvinients', true);
            }

        }
        public function delRdv() {
            require_once('src/config/Header.php');
            require_once('src/model/connection.php');
            if($_SERVER['REQUEST_METHOD'] == 'GET') {
                $params = explode('/', $_GET['p']);
                // echo $params[2];
                $db = new Database();
                $result = $db->delete('rdv', $params[2]);
                if($result){
                    echo Database::message('le rendez-vous est annullé avec succes', false);
                }else {
                    echo Database::message('l`operation est echouer!', true);
                }
            }else {
                echo Database::message('Probleme de serveur', true);
            }
        }



    }
