<?php
    class Loan
    {
        private $date_out;
        private $date_due;
        private $date_returned;
        private $copy_id;
        private $patron_id;
        private $id;

        function __construct($date_out, $date_due, $date_returned = null, $copy_id, $patron_id, $id = null)
        {
            $this->date_out = date_create($date_out);
            $this->date_due = date_create($date_due);
            if ($date_returned != null) {
                $this->date_returned = date_create($date_returned);
            }
            $this->copy_id = $copy_id;
            $this->patron_id = $patron_id;
            $this->id = $id;
        }

        function getDate($date_type)
        {
            switch($date_type) {
                case "date_out":
                    return date_format($this->date_out, 'Y-m-d');
                    break;
                case "date_due":
                    return date_format($this->date_due, 'Y-m-d');
                    break;
                case "date_returned":
                    if ($this->date_returned)
                    {
                        return date_format($this->date_returned, 'Y-m-d');
                        break;
                    } else {
                        return null;
                        break;
                    }
                default:
                        return "Pick something, hoss.";
            }
        }

        function setDate($date_type, $new_date)
        {
            switch($date_type) {
                case "date_out":
                    $this->date_out = date_create($new_date);
                    break;
                case "date_due":
                    $this->date_due = date_create($new_date);
                    break;
                case "date_returned":
                    $this->date_returned = date_create($new_date);
                    break;
                default:
                        return "Pick something, hoss.";
            }
        }

        function getEntityId($entity_type)
        {
            switch($entity_type) {
                case "patron_id":
                    return $this->patron_id;
                    break;
                case "copy_id":
                    return $this->copy_id;
                    break;
                default:
                    return "Select a valid option";
            }
        }

        function setEntityId($entity_type, $new_id)
        {
            switch($entity_type) {
                case "patron_id":
                    $this->patron_id = $new_id;
                    break;
                case "copy_id":
                    $this->copy_id = $new_id;
                    break;
                default:
                    return "Select a valid option";
            }
        }

        function getId()
        {
            return $this->id;
        }

        function setId($new_id)
        {
            $this->id = (int) $new_id;
        }

        function save()
        {
            if ($this->getDate('date_returned') != null) {
                $return_date = "'{$this->getDate('date_returned')}'";
            } else {
                $return_date = "NULL";
            }
            $GLOBALS['DB']->exec("INSERT INTO loans (date_out, date_due, date_returned, copy_id, patron_id) VALUES ('{$this->getDate('date_out')}', '{$this->getDate('date_due')}', $return_date, {$this->getEntityId('copy_id')}, {$this->getEntityId('patron_id')});");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll()
        {
            $returned_loan = $GLOBALS['DB']->query("SELECT * FROM loans;");
            $loans = array();
            foreach($returned_loan as $loan) {
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

        static function allOverdue()
        {
            $returned_loan = $GLOBALS['DB']->query("SELECT * FROM loans WHERE date_due < CURRENT_DATE AND date_returned IS NULL;");
            $loans = array();
            foreach($returned_loan as $loan) {
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

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM loans;");
        }

        static function find($search_id)
        {
            $found_loan = null;
            $loans = Loan::getAll();
            foreach($loans as $loan) {
                $loan_id = $loan->getId();
                if ($loan_id == $search_id) {
                  $found_loan = $loan;
                }
            }
            return $found_loan;
        }

        function updateDueDate($new_due_date)
        {
            $GLOBALS['DB']->exec("UPDATE loans SET date_due = '{$new_due_date}' WHERE id = {$this->getId()};");
            $this->setDate('date_due', $new_due_date);
        }

        function updateReturnDate($new_return_date)
        {
            $GLOBALS['DB']->exec("UPDATE loans SET date_returned = '{$new_return_date}' WHERE id = {$this->getId()};");
            $this->setDate('date_returned', $new_return_date);
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM loans WHERE id = {$this->getId()};");
        }

        function getPatron()
        {
            $patron_id = $this->getEntityId('patron_id');
            return Patron::find($patron_id);
        }

        function getTitle()
        {
            $copy_id = $this->getEntityId('copy_id');
            $titles = $GLOBALS['DB']->query("SELECT * FROM loans JOIN copies ON (loans.copy_id = copies.id) JOIN titles ON (titles.id = copies.title_id) WHERE copy_id = {$copy_id};");
            foreach ($titles as $title) {
                return $title['name'];
            }
        }
    }
?>
