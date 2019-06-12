<?php
namespace api\books;

class Books
{
    private $_db;
    function __construct($db) {
        $this->_db = $db;
    }
    function getBook($id){
        $toReturn = [];
        $stmt = $this->_db->prepare("SELECT name,Author from books where id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        while($row = $stmt->fetch(\PDO::FETCH_OBJ)){
            $toReturn['name'] =  $row->name;
            $toReturn['Author'] =  $row->Author;
        }
        return $toReturn;
    }
    function getBooks(){
        $toReturn = [];
        $stmt = $this->_db->prepare("SELECT name,Author from books");
        $stmt->execute();
        while($row = $stmt->fetch(\PDO::FETCH_OBJ)){
            array_push($toReturn,['name' => $row->name, 'Author' =>$row->name]);
        }
        return $toReturn;
    }
}
