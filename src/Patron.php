<?php
    class Patron
    {
        private $name;
        private $id;

        function __construct($name, $id = null)
        {
            $this->name = $name;
            $this->id = $id;
        }

        function getName()
        {
            return $this->name;
        }

        function getId()
        {
            return $this->id;
        }

        function setName($new_name)
        {
            $this->name = (string) $new_name;
        }

        function setId($new_id)
        {
            $this->id = (int) $new_id;
        }

        function save()
        {
          $GLOBALS['DB']->exec("INSERT INTO patrons (name) VALUES ('{$this->getName()}')");
          $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll()
        {
            $returned_patron = $GLOBALS['DB']->query("SELECT * FROM patrons;");
            $patrons = array();
            foreach($returned_patron as $patron) {
                $name = $patron['name'];
                $id = $patron['id'];
                $new_patron = new Patron($name, $id);
                array_push($patrons, $new_patron);
            }
            return $patrons;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM patrons;");
        }

        static function find($search_id)
        {
            $found_patron = null;
            $patrons = Patron::getAll();
            foreach($patrons as $patron) {
                $patron_id = $patron->getId();
                if ($patron_id == $search_id) {
                  $found_patron = $patron;
                }
            }
            return $found_patron;
        }

        function update($new_name)
        {
            $GLOBALS['DB']->exec("UPDATE patrons SET name = '{$new_name}' WHERE id = {$this->getId()};");
            $this->setName($new_name);
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM patrons WHERE id = {$this->getId()};");
        }

        function getLoans()
        {
            $loans = array();
            $returned_loans = $GLOBALS['DB']->query("SELECT * FROM loans WHERE patron_id = {$this->getId()};");
            foreach ($returned_loans as $loan) {
                $id = $loan['id'];
                $date_out = $loan['date_out'];
                $date_due = $loan['date_due'];
                $date_returned = $loan['date_returned'];
                $copy_id = $loan['copy_id'];
                $patron_id = $loan['patron_id'];
                $new_loan = new Loan($date_out, $date_due, $date_returned, $copy_id, $patron_id, $id);
                array_push($loans, $new_loan);
            }
            return $loans;
        }

    }
?>
