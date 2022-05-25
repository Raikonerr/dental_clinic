<?php


class Authentication
{
    public function  __construct()
    {
    }

    public function signup($email, $nom, $prenom, $age, $ddn)
    {
        // echo uniqid("gfg");
        // die;
        require_once('connection.php');
        $db = new Database();
        if (empty($db->selectOne('client', 'email', $email))) {
            $result = $db->insert('client', ['idC', 'email', 'nom', 'prenom', 'age', 'date_de_naissance'], [uniqid('gfg'), $email, $nom, $prenom, $age, $ddn]);
            if ($result) {
            
                return $db->selectOne('client', 'email', $email);
            } else {
                return false;
            }
        }
    }

    public function signin($id)
    {
        require_once('connection.php');
        $db = new Database();
        $result = $db->checkUserExist($id);
        return $result;
    }


    public static function message($content, $status)
    {
        return json_encode(array('message' => $content, 'error' => $status));
    }

    

}
