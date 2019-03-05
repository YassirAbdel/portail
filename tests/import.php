<?php


use League\Csv\Reader;
//use Doctrine\DBAL\Driver\PDOStatement;

$dsn = 'mysql:host=localhost;dbname=portail';
$username = 'root';
$password = 'root';
$options = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
);

$dbh = new PDO($dsn, $username, $password, $options);

//We are going to insert some data into the users table
//$dbh = new PDO($dsn);

$sth = $dbh->prepare(
    //"INSERT INTO users (firstname, lastname, email) VALUES (:firstname, :lastname, :email)"
    "INSERT INTO resource (id, comment, oai, type, title, lang, person, oeuvre, organisme, geo, tag, analyse, rights, auteur, resp1, editeur, editeurlieu, anneedit, isbn, pagination, collection) VALUES (:id, :comment, :oai, :type, :title, :lang, :person, :oeuvre, :organisme, :geo, :tag, :analyse, :rights, :auteur, :resp1, :editeur, :editeurlieu, :anneedit, :isbn, :pagination, :collection)"
    );

$csv = Reader::createFromPath('/Applications/MAMP/htdocs/portail/test02.cvs', 'r');
$csv->setOffset(1); //because we don't want to insert the header
$nbInsert = $csv->each(function ($row) use (&$sth) {
    //Do not forget to validate your data before inserting it in your database
    $sth->bindValue(':firstname', $row[0], PDO::PARAM_STR);
    $sth->bindValue(':lastname', $row[1], PDO::PARAM_STR);
    $sth->bindValue(':email', $row[2], PDO::PARAM_STR);
    
    return $sth->execute(); //if the function return false then the iteration will stop
});