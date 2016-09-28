<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Copy.php";

    $server = 'mysql:host=localhost;dbname=bibliotheque_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class CopyTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Copy::deleteAll();
        }

        function test_getTitleId()
        {
            // Arrange
            $title_id = 1;
            $id = null;
            $test_copy = new Copy($title_id, $id);
            // Act
            $result = $test_copy->getTitleId();
            // Assert
            $this->assertEquals($title_id, $result);
        }

        function test_getId()
        {
            // Arrange
            $title_id = 1;
            $id = null;
            $test_copy = new Copy($title_id, $id);
            $test_copy->save();
            // Act
            $result = $test_copy->getId();
            // Assert
            $this->assertEquals($test_copy->getId(), $result);
        }

        function test_setTitleId()
        {
            // Arrange
            $title_id = 1;
            $id = null;
            $test_copy = new Copy($title_id, $id);
            $test_copy->save();
            $newtitle_id = 2;
            // Act
            $test_copy->setTitleId($newtitle_id);
            $result = $test_copy->getTitleId();
            // Assert
            $this->assertEquals($newtitle_id, $result);
        }

        function test_getAll()
        {
            // Arrange
            $title_id = 1;
            $id = null;
            $test_copy1 = new Copy($title_id, $id);
            $test_copy1->save();
            $title_id = 3;
            $test_copy2 = new Copy($title_id, $id);
            $test_copy2->save();
            // Act
            $result = Copy::getAll();
            // Assert
            $this->assertEquals([$test_copy1, $test_copy2], $result);
        }

        function test_save()
        {
            // Arrange
            $title_id = 1;
            $id = null;
            $test_copy1 = new Copy($title_id, $id);
            $test_copy1->save();
            // Act
            $result = Copy::getAll();
            // Assert
            $this->assertEquals([$test_copy1], $result);
        }

        function test_deleteAll()
        {
            // Arrange
            $title_id = 1;
            $id = null;
            $test_copy1 = new Copy($title_id, $id);
            $test_copy1->save();
            $title_id = 3;
            $test_copy2 = new Copy($title_id, $id);
            $test_copy2->save();
            // Act
            Copy::deleteAll();
            // Assert
            $this->assertEquals([], Copy::getAll());
        }

        function test_find()
        {
            // Arrange
            $title_id = 1;
            $id = null;
            $test_copy1 = new Copy($title_id, $id);
            $test_copy1->save();
            $title_id = 3;
            $test_copy2 = new Copy($title_id, $id);
            $test_copy2->save();
            // Act
            $result = Copy::find($test_copy1->getId());
            // Assert
            $this->assertEquals($test_copy1, $result);
        }

        function test_update()
        {
            // Arrange
            $title_id = 1;
            $id = null;
            $test_copy1 = new Copy($title_id, $id);
            $test_copy1->save();
            $newtitle_id = 2;
            // Act
            $test_copy1->update($newtitle_id);
            $result = Copy::find($test_copy1->getId())->getTitleId();
            // Assert
            $this->assertEquals($newtitle_id, $result);
        }

        function test_delete()
        {
            // Arrange
            $title_id = 1;
            $id = null;
            $test_copy1 = new Copy($title_id, $id);
            $test_copy1->save();
            $title_id = 3;
            $test_copy2 = new Copy($title_id, $id);
            $test_copy2->save();
            $title_id = 4;
            $test_copy3 = new Copy($title_id, $id);
            $test_copy3->save();
            // Act
            $test_copy1->delete();
            $result = Copy::getAll();
            // Assert
            $this->assertEquals([$test_copy2, $test_copy3], $result);
        }

    }
?>
