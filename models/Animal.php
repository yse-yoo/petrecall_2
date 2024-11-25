<?php
class Animal {

    public $values = [
        [
            "id" => 1,
            "name" => "犬",
        ],
        [
            "id" => 2,
            "name" => "猫",
        ],
        [
            "id" => 3,
            "name" => "鳥",
        ],
    ];

    function fetch($id)  {
        // TODO: Database
        foreach ($this->getList() as $value) {
            if ($id == $value['id']) return $value;
        }
    }
    
    function getList()  {
        // TODO: Database
        return $this->values;
    }

}
?>