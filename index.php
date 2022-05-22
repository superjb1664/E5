<?php

$bdd = new PDO('mysql:host=127.0.0.1;dbname=gsb;charset=utf8', 'root', 'secret');



echo "<H1>Logiciel E5 - RH !!!</H1>";

if (function_exists('curl_init'))
{
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, "https://www.jbaubry.fr/coursEnLigne/E5.php");
    curl_setopt($curl, CURLOPT_PORT, "443");
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $output = curl_exec($curl);
    if($output == "32631827")
         echo "<H2>jbaubry.fr : OK</H2>";
    else
        echo "<H2>jbaubry.fr : pas OK</H2>";
}
else
{
    echo "<br>'apt install php-curl' puis redémarrer apache 2<br>";
}

if(isset($_REQUEST["etat"])) {
    $etat = (int) $_REQUEST["etat"];
}
else
    $etat = 0;
switch($etat)
{
    case    0:
        AfficherListe($bdd);
        AfficherAjouterLigne();
        break;
    case 1:
        AfficherFormulaireAjout();
        break;
    case 2:
        if(isset($_REQUEST["valeur"]))
        {
            if($_REQUEST["valeur"] != "")
            {

                AjouterValeur($bdd, $_REQUEST["valeur"]);
                echo " $_REQUEST[valeur] ajouté !";
            }
        }
        AfficherListe($bdd);
        AfficherAjouterLigne();
        break;
}

function AjouterValeur($bdd, $valeur)
{
    $req = $bdd->prepare('INSERT INTO table_www( value) VALUES( :value)');
    $req->execute(array(
        'value' => $valeur
    ));
}

function AfficherFormulaireAjout()
{
    echo "
<div>
    <form> 
        Valeur : <input type='text' name='valeur'><br>
        <input type='hidden' name='etat' value='2'>
        <input type='submit' value ='Ajouter'>
    </form>
</div>";
}

function AfficherAjouterLigne(){
    echo "
<div>
    <form> 
        <input type='hidden' name='etat' value='1'>
        <input type='submit' value ='ajouter'>
    </form>
</div>";
}
function AfficherListe($bdd)
{

    $reponse = $bdd->query('SELECT * FROM table_www');
    $table = $reponse->fetchAll();
// On affiche chaque entrée une à une
    echo "<table>
            <tr>
                <th> id </th><th>valeur</th>
            </tr> 
"           ;
    if(count($table) > 0) {
        foreach ($table as $ligne) {
            echo "
                    <tr>
                        <td> $ligne[id] </td><td> $ligne[value]</td>
                    </tr> 
        ";
        }
    }
    else
    {
        echo "<tr><td colspan='2'>Pas encore d'enregistrement</td></tr>";
    }
    echo "</table>";
}