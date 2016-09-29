<?php
    class Copy
    {
        private $title_id;
        private $id;

        function __construct($title_id, $id = null)
        {
            $this->title_id = $title_id;
            $this->id = $id;
        }

        function getTitleId()
        {
            return $this->title_id;
        }

        function getId()
        {
            return $this->id;
        }

        function setTitleId($new_title_id)
        {
            $this->title_id = (int) $new_title_id;
        }

        function setId($new_id)
        {
            $this->id = (int) $new_id;
        }

        function save()
        {
          $GLOBALS['DB']->exec("INSERT INTO copies (title_id) VALUES ('{$this->getTitleId()}')");
          $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll()
        {
            $returned_copy = $GLOBALS['DB']->query("SELECT * FROM copies;");
            $copies = array();
            foreach($returned_copy as $copy) {
                $title_id = $copy['title_id'];
                $id = $copy['id'];
                $new_copy = new Copy($title_id, $id);
                array_push($copies, $new_copy);
            }
            return $copies;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM copies;");
        }

        static function find($search_id)
        {
            $found_copy = null;
            $copies = Copy::getAll();
            foreach($copies as $copy) {
                $copy_id = $copy->getId();
                if ($copy_id == $search_id) {
                  $found_copy = $copy;
                }
            }
            return $found_copy;
        }

        function update($new_title_id)
        {
            $GLOBALS['DB']->exec("UPDATE copies SET title_id = '{$new_title_id}' WHERE id = {$this->getId()};");
            $this->setTitleId($new_title_id);
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM copies WHERE id = {$this->getId()};");
        }

        function isCheckedOut()
        {
            $copy_id = $this->id;
            $returned_copies = $GLOBALS['DB']->query("SELECT * FROM loans JOIN copies ON (copies.id = loans.copy_id) WHERE copies.id = {$copy_id};");
            foreach ($returned_copies as $copy) {
                if ($copy['date_returned'] == null) {
                    return true;
                }
            }
            return false;
        }

    }
?>
