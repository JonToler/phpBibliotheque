<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Loan.php";
    require_once "src/Patron.php";

    $server = 'mysql:host=localhost;dbname=bibliotheque_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class LoanTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Loan::deleteAll();
            Patron::deleteAll();
        }

        function test_getDateAndEntity()
        {
            // Arrange
            $date1 = "2016-09-28";
            $date2 = "2016-10-02";
            $date3 = "2016-10-01";
            $patron_name = "Bob Smith";
            $copy_id = 1;
            $new_patron = new Patron($patron_name);
            $test_loan = new Loan($date1, $date2, $date3, $copy_id, $new_patron->getId());

            // Act
            $result1 = $test_loan->getDate('date_out');
            $result2 = $test_loan->getDate('date_due');
            $result3 = $test_loan->getDate('date_returned');
            $result4 = $test_loan->getEntityId('copy_id');
            $result5 = $test_loan->getEntityId('patron_id');

            // Assert
            $this->assertEquals([$date1, $date2, $date3, $copy_id, $new_patron->getId()], [$result1, $result2, $result3, $result4, $result5]);
        }

        function test_getId()
        {
            // Arrange
            $loan_id = 10;
            $date1 = "2016-09-28";
            $date2 = "2016-10-02";
            $date3 = "2016-10-01";
            $patron_name = "Bob Smith";
            $copy_id = 1;
            $new_patron = new Patron($patron_name);
            $test_loan = new Loan($date1, $date2, $date3, $copy_id, $new_patron->getId(), $loan_id);
            // Act
            $result = $test_loan->getId();
            // Assert
            $this->assertEquals($loan_id, $result);
        }

        function test_setDate()
        {
            // Arrange
            $loan_id = 10;
            $date1 = "2016-09-28";
            $date2 = "2016-10-02";
            $date3 = "2016-10-01";
            $patron_name = "Bob Smith";
            $copy_id = 1;
            $new_patron = new Patron($patron_name);
            $test_loan = new Loan($date1, $date2, $date3, $copy_id, $new_patron->getId(), $loan_id);
            // Act
            $test_loan->setDate('date_out', $date2);
            $test_loan->setDate('date_due', $date3);
            $test_loan->setDate('date_returned', $date1);
            $test_loan->setEntityId("copy_id", 2);
            $test_loan->setEntityId("patron_id", 2);
            $result = array($test_loan->getDate("date_out"),$test_loan->getDate("date_due"),$test_loan->getDate("date_returned"),$test_loan->getEntityId("patron_id"),$test_loan->getEntityId("copy_id"));
            // Assert
            $this->assertEquals([$date2,$date3,$date1,2,2], $result);
        }

        function test_getAll()
        {
            // Arrange
            $loan_id = 10;
            $date1 = "2016-09-28";
            $date2 = "2016-10-02";
            $date3 = "2016-10-01";
            $patron_name = "Bob Smith";
            $copy_id = 1;
            $new_patron = new Patron($patron_name);
            $new_patron->save();
            $test_loan = new Loan($date1, $date2, $date3, $copy_id, $new_patron->getId(), $loan_id);
            $test_loan->save();
            $test_loan2= new Loan($date2, $date3, $date1, $copy_id,3);
            $test_loan2->save();

            // Act
            $result = Loan::getAll();
            // Assert
            $this->assertEquals([$test_loan, $test_loan2], $result);
        }

        function test_save()
        {
            // Arrange
            $date1 = "2016-09-28";
            $date2 = "2016-10-02";
            $date3 = "2016-10-01";
            $patron_name = "Bob Smith";
            $copy_id = 1;
            $new_patron = new Patron($patron_name);
            $new_patron->save();
            $test_loan = new Loan($date1, $date2, $date3, $copy_id, $new_patron->getId());
            $test_loan->save();
            // Act
            $result = Loan::getAll();
            // Assert
            $this->assertEquals([$test_loan], $result);
        }

        function test_deleteAll()
        {
            // Arrange
            $loan_id = 10;
            $date1 = "2016-09-28";
            $date2 = "2016-10-02";
            $date3 = "2016-10-01";
            $patron_name = "Bob Smith";
            $copy_id = 1;
            $new_patron = new Patron($patron_name);
            $new_patron->save();
            $test_loan = new Loan($date1, $date2, $date3, $copy_id, $new_patron->getId(), $loan_id);
            $test_loan->save();
            $test_loan2= new Loan($date2, $date3, $date1, $copy_id,3);
            $test_loan2->save();

            // Act
            Loan::deleteAll();
            // Assert
            $this->assertEquals([], Loan::getAll());
        }

        function test_find()
        {
            // Arrange
            $loan_id = 10;
            $date1 = "2016-09-28";
            $date2 = "2016-10-02";
            $date3 = "2016-10-01";
            $patron_name = "Bob Smith";
            $copy_id = 1;
            $new_patron = new Patron($patron_name);
            $new_patron->save();
            $test_loan = new Loan($date1, $date2, $date3, $copy_id, $new_patron->getId(), $loan_id);
            $test_loan->save();
            $test_loan2= new Loan($date2, $date3, $date1, $copy_id,3);
            $test_loan2->save();

            // Act
            $result = Loan::find($test_loan->getId());
            // Assert
            $this->assertEquals($test_loan, $result);
        }

        function test_update()
        {
            // Arrange
            $loan_id = 10;
            $date1 = "2016-09-28";
            $date2 = "2016-10-02";
            $date3 = "2016-10-01";
            $patron_name = "Bob Smith";
            $copy_id = 1;
            $new_patron = new Patron($patron_name);
            $new_patron->save();
            $test_loan = new Loan($date1, $date2, $date3, $copy_id, $new_patron->getId(), $loan_id);
            $test_loan->save();
            $test_loan2= new Loan($date2, $date3, $date1, $copy_id,3);
            $test_loan2->save();

            // Act
            $test_loan->updateDueDate($date3);
            $result = Loan::find($test_loan->getId())->getDate("date_due");
            // Assert
            $this->assertEquals($date3, $result);
        }

        function test_update2()
        {
            // Arrange
            $loan_id = 10;
            $date1 = "2016-09-28";
            $date2 = "2016-10-02";
            $date3 = "2016-10-01";
            $patron_name = "Bob Smith";
            $copy_id = 1;
            $new_patron = new Patron($patron_name);
            $new_patron->save();
            $test_loan = new Loan($date1, $date2, $date3, $copy_id, $new_patron->getId(), $loan_id);
            $test_loan->save();
            $test_loan2= new Loan($date2, $date3, $date1, $copy_id,3);
            $test_loan2->save();

            // Act
            $test_loan->updateReturnDate($date3);
            $result = Loan::find($test_loan->getId())->getDate("date_returned");
            // Assert
            $this->assertEquals($date3, $result);
        }

        function test_delete()
        {
            // Arrange
            $loan_id = 10;
            $date1 = "2016-09-28";
            $date2 = "2016-10-02";
            $date3 = "2016-10-01";
            $patron_name = "Bob Smith";
            $copy_id = 1;
            $new_patron = new Patron($patron_name);
            $new_patron->save();
            $test_loan = new Loan($date1, $date2, $date3, $copy_id, $new_patron->getId(), $loan_id);
            $test_loan->save();
            $test_loan2= new Loan($date2, $date3, $date1, $copy_id,3);
            $test_loan2->save();
            // Act
            $test_loan->delete();
            $result = Loan::getAll();
            // Assert
            $this->assertEquals([$test_loan2], $result);
        }

    }
?>
