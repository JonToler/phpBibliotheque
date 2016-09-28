<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Patron.php";

    $server = 'mysql:host=localhost;dbname=bibliotheque_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class PatronTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Patron::deleteAll();
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

    }
?>
