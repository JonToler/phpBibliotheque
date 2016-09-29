<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Patron.php";
    require_once "src/Copy.php";
    require_once "src/Title.php";
    require_once "src/Author.php";
    require_once "src/Loan.php";

    $server = 'mysql:host=localhost;dbname=bibliotheque_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class PatronTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Patron::deleteAll();
            Copy::deleteAll();
            Title::deleteAll();
            Author::deleteAll();
            Loan::deleteAll();
        }

        function test_getName()
        {
            // Arrange
            $name = "Joe McCool";
            $id = null;
            $test_patron = new Patron($name, $id);
            // Act
            $result = $test_patron->getName();
            // Assert
            $this->assertEquals($name, $result);
        }

        function test_getId()
        {
            // Arrange
            $name = "Joe McCool";
            $id = 10;
            $test_patron = new Patron($name, $id);
            // Act
            $result = $test_patron->getId();
            // Assert
            $this->assertEquals($id, $result);
        }

        function test_setName()
        {
            // Arrange
            $name = "Joe McCool";
            $id = null;
            $test_patron = new Patron($name, $id);
            $test_patron->save();
            $new_name = "Joseph McCool";
            // Act
            $test_patron->setName($new_name);
            $result = $test_patron->getName();
            // Assert
            $this->assertEquals($new_name, $result);
        }

        function test_getAll()
        {
            // Arrange
            $name = "Joe McCool";
            $id = null;
            $test_patron1 = new Patron($name, $id);
            $test_patron1->save();
            $name = "Toni Thyme";
            $test_patron2 = new Patron($name, $id);
            $test_patron2->save();
            // Act
            $result = Patron::getAll();
            // Assert
            $this->assertEquals([$test_patron1, $test_patron2], $result);
        }

        function test_save()
        {
            // Arrange
            $name = "Joe McCool";
            $id = null;
            $test_patron1 = new Patron($name, $id);
            $test_patron1->save();
            // Act
            $result = Patron::getAll();
            // Assert
            $this->assertEquals([$test_patron1], $result);
        }

        function test_deleteAll()
        {
            // Arrange
            $name = "Joe McCool";
            $id = null;
            $test_patron1 = new Patron($name, $id);
            $test_patron1->save();
            $name = "Toni Thyme";
            $test_patron2 = new Patron($name, $id);
            $test_patron2->save();
            // Act
            Patron::deleteAll();
            // Assert
            $this->assertEquals([], Patron::getAll());
        }

        function test_find()
        {
            // Arrange
            $name = "Joe McCool";
            $id = null;
            $test_patron1 = new Patron($name, $id);
            $test_patron1->save();
            $name = "Toni Thyme";
            $test_patron2 = new Patron($name, $id);
            $test_patron2->save();
            // Act
            $result = Patron::find($test_patron1->getId());
            // Assert
            $this->assertEquals($test_patron1, $result);
        }

        function test_update()
        {
            // Arrange
            $name = "Joe McCool";
            $id = null;
            $test_patron1 = new Patron($name, $id);
            $test_patron1->save();
            $new_name = "Joseph McCool";
            // Act
            $test_patron1->update($new_name);
            $result = Patron::find($test_patron1->getId())->getName();
            // Assert
            $this->assertEquals($new_name, $result);
        }

        function test_delete()
        {
            // Arrange
            $name = "Joe McCool";
            $id = null;
            $test_patron1 = new Patron($name, $id);
            $test_patron1->save();
            $name = "Toni Thyme";
            $test_patron2 = new Patron($name, $id);
            $test_patron2->save();
            $name = "Jane Morrison";
            $test_patron3 = new Patron($name, $id);
            $test_patron3->save();
            // Act
            $test_patron1->delete();
            $result = Patron::getAll();
            // Assert
            $this->assertEquals([$test_patron2, $test_patron3], $result);
        }

        function test_getLoans()
        {
            // Arrange
            $name = "Joe McCool";
            $test_patron1 = new Patron($name);
            $test_patron1->save();
            $author_name = "Brian Herbert";
            $new_author = new Author($author_name);
            $new_author->save();
            $author_name2 = "Kevin Anderson";
            $new_author2 = new Author($author_name2);
            $new_author2->save();
            $title_name = "The Milkman of Dune";
            $new_title = new Title($title_name);
            $new_title->save();
            $new_title->addAuthor($new_author);
            $new_title->addAuthor($new_author2);
            $title_name2 = "Neuromancing the Stone";
            $new_title2 = new Title($title_name2);
            $new_title2->save();
            $new_title2->addAuthor($new_author);
            $copy1 = new Copy($new_title->getId());
            $copy1->save();
            $copy2 = new Copy($new_title->getId());
            $copy2->save();
            $copy3 = new Copy($new_title2->getId());
            $copy3->save();
            $copy4 = new Copy($new_title2->getId());
            $copy4->save();
            $date1 = date("Y-m-d");
            $date2 = "2016-10-02";
            $date3 = "2016-10-01";
            $loan1 = new Loan($date1, $date2, $date3, $copy1->getId(), $test_patron1->getId());
            $loan1->save();
            $loan2 = new Loan($date1, $date2, null, $copy3->getId(), $test_patron1->getId());
            $loan2->save();
            $loan3 = new Loan($date1, $date2, null, $copy2->getId(), $test_patron1->getId());
            $loan3->save();

            // Act
            $result = $test_patron1->getLoans();

            // Assert
            $this->assertEquals(Loan::getAll(), $result);
        }

    }
?>
