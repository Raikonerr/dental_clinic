<?php

require_once 'src/security/jwt.php';

class ClientController extends JwtController
{
    public function __construct()
    {
    }

    public function index()
    {
        echo 'l index';
    }

    public function signin()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            require_once('src/model/authentication.php');
            require_once('src/config/Header.php');
            if (isset($_POST['idC'])) {
                $auth = new Authentication();
                $result = $auth->signin($_POST['idC']);
                if ($result) {
                    echo Authentication::message('Bienvenue', false);
                } else {
                    echo Authentication::message('l`utilisateur n`existe pas', true);
                }
            } else {
                echo Authentication::message('le champ est vide', true);
            }
        }
    }

    public function signup()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            require_once('src/model/authentication.php');
            require_once('src/config/Header.php');
            if (isset($_POST['email']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['age']) && isset($_POST['date_de_naissance'])) {
                if (!empty($_POST['email']) && !empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['age']) && !empty($_POST['date_de_naissance'])) {
                    $auth = new Authentication();
                    $res = $auth->signup($_POST['email'], $_POST['nom'], $_POST['prenom'], $_POST['age'], $_POST['date_de_naissance']);
                    if ($res) {

                        echo Authentication::message($res, false);
                    } else {
                        echo Authentication::message('l`utilisateur existe deja!', false);
                    }
                } else {
                    echo Authentication::message('le champ est vide', true);
                }
            } else {
                echo Authentication::message('Erreur 4xx', true);
            }
        } else {
            echo Authentication::message('l`erreur 5xx', true);
        }
    }
    public function read($date)
    {
date_default_timezone_set('Africa/Casablanca');
        require_once('src/config/header.php');
        require_once('src/model/connection.php');
        $result = new Database();
        $dateToday = date("Y-m-d");
        $heureNow = date("H:i:s");
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            $res = $result->read($date);

            if ($res) {
                if ($date < $dateToday) {

                    foreach ($res as $key => $value) {
                        array_splice($res[$key],0);
                    }
                } elseif ($date == $dateToday) {
                    // json_encode(["message" => ]);
                    foreach ($res as $key => $value) {
                        if ($value['debut'] < $heureNow) {
                            array_splice($res[$key],0);

                            // echo json_encode(["message" => $value['debut']]);
                        }
                    }
                    
                } 
            }
                if($res){
                    echo json_encode($res);

                }


        } else  echo json_encode(
            array('message' => 'change method to GET')
        );
    }

    public function addRdv()
    {
        require_once('src/config/header.php');
        require_once('src/model/connection.php');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!empty($_POST['idC']) && !empty($_POST['sujet_rdv']) && !empty($_POST['idCr'] && !empty($_POST['date']))) {
                $db = new Database();
                $res = $db->insert('rdv', ['sujet_rdv', 'idCr', 'idC', 'date'], [$_POST['sujet_rdv'], $_POST['idCr'], $_POST['idC'], $_POST['date']]);
                if ($res) {
                    echo Database::message('Merci pour votre confiance', false);
                } else {
                    echo Database::message('Echouer!', true);
                }
            } else {
                echo Database::message('Veuillez remplir tout le form', true);
            }
        } else {
            echo Database::message('probleme du serveur , desole pour les inconvinients', true);
        }
    }
    public function delRdv()
    {
        require_once('src/config/Header.php');
        require_once('src/model/connection.php');
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $params = explode('/', $_GET['p']);
            // echo $params[2];
            $db = new Database();
            $result = $db->delete('rdv', $params[2]);

            if ($result) {
                echo Database::message('le rendez-vous est annullé avec succes', false);
            } else {
                echo Database::message('l`operation est echouer!', true);
            }
        } else {
            echo Database::message('Probleme de serveur', true);
        }
    }

    public function getRdv($id)
    {

        require_once('src/config/Header.php');
        require_once('src/model/connection.php');
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $params = explode('/', $_GET['p']);
            // echo $params[2];
            $db = new Database();
            $result = $db->readSingleRdv($id);
            if ($result) {
                echo Database::message($result, false);
            } else {
                echo Database::message('l`operation est echouer!', true);
            }
        } else {
            echo Database::message('Probleme de serveur', true);
        }
    }


    public function updateRdv()
    {
        require_once('src/config/Header.php');
        require_once('src/model/connection.php');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $params = explode('/', $_GET['p']);
            $data = json_decode(file_get_contents('php://input'));
            // echo $data->sujet_rdv;
            echo json_encode(["tt" => $data->sujet_rdv]);
            // echo $params[2];
            $db = new Database();
            $result = $db->update('rdv', $data->sujet_rdv, $data->date, $params[2]);
            if ($result) {
                echo Database::message('le rendez-vous est modifier avec succes', false);
            } else {
                echo Database::message('l`operation est echouer!', true);
            }
        }
    }
}
