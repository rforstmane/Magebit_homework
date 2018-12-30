<?php

require_once('Config.php');

class Attributes
{
    private $dbConnection;
    private $app;
    public $user_id;
    public $keey;
    public $value;

    public function __construct($instance)
    {
        $this->app = &$instance;
        $this->dbConnection = new mysqli(Config::HOST, Config::USER, Config::PASSWORD, Config::DATABASE);
    }

    public function __destruct()
    {
        $this->dbConnection->close();
    }

    public function mergeFormInput($id_array, $keey_array, $value_array)
    {
        $all_inputs_array = [];
        foreach ($id_array as $i => $item_id) {
            $object = new stdClass();
            $object->id = $this->dbConnection->escape_string($id_array[$i]);
            $object->key = $this->dbConnection->escape_string($keey_array[$i]);
            $object->value = $this->dbConnection->escape_string($value_array[$i]);
            array_push($all_inputs_array, $object);
        }
        return $all_inputs_array;
    }

    public function storeNewInputs($inputs_array, $user_id)
    {
        $new_inputs = [];
        foreach ($inputs_array as $item) {
            if ($item->id == "") {
                array_push($new_inputs, $item);
            }
        }

        foreach ($new_inputs as $input) {
            if (!(empty($input->key) && empty($input->value))) {
                $mysql = "INSERT INTO attributes (user_id, keey, value) VALUES ('$user_id', '$input->key', '$input->value') ";
                $this->dbConnection->query($mysql);
            }
        }
    }

    public function getPotentialUpdated($inputs_array)
    {
        $potential_updated_inputs = [];
        foreach ($inputs_array as $item) {
            if ($item->id != "") {
                array_push($potential_updated_inputs, $item);
            }
        }
        return $potential_updated_inputs;
    }

    public function joinIds($potential_updated_inputs)
    {
        $id_array = [];
        foreach ($potential_updated_inputs as $item) {
            array_push($id_array, $item->id);
        }

        $result = join(", ", $id_array);
        return $result;
    }

    public function getOldInputs($potential_updated_inputs, $user_id)
    {
        $old_inputs_query = "SELECT * FROM attributes WHERE user_id='$user_id' AND id IN (" . $this->joinIds($potential_updated_inputs) . ") ";
        $old_inputs_result = $this->dbConnection->query($old_inputs_query);
        if ($old_inputs_result != false) {
            $old_inputs_rows = $old_inputs_result->fetch_all(MYSQLI_ASSOC);
            foreach ($old_inputs_rows as $i => $row) {
                $old_inputs_rows[$i] = (object)$row;
            }
            return $old_inputs_rows;
        } else {
            return [];
        }
    }

    public function getAttributesByUserId()
    {
        $user_id = $_SESSION['user_id'];
        $attr_query = "SELECT * FROM attributes WHERE user_id ='$user_id' ";
        $attr_result = $this->dbConnection->query($attr_query);
        $attr_rows = $attr_result->fetch_all(MYSQLI_ASSOC);

        return $attr_rows;
    }

    public function getAllAttributes($user_id)
    {
        $old_inputs_query = "SELECT * FROM attributes WHERE user_id ='$user_id' ";
        $old_inputs_result = $this->dbConnection->query($old_inputs_query);
        $old_inputs_rows = $old_inputs_result->fetch_all(MYSQLI_ASSOC);

        foreach ($old_inputs_rows as $i => $row) {
            $old_inputs_rows[$i] = (object)$row;
        }
        return $old_inputs_rows;
    }

    public function findInputById($id, $searchable_array)
    {
        foreach ($searchable_array as $item) {
            if ($item->id == $id) {
                return $item;
            }
        }
        return null;
    }

    public function updateExistingInputs($inputs_array, $user_id)
    {
        $potential_updated_inputs = $this->getPotentialUpdated($inputs_array);
        $old_inputs = $this->getOldInputs($potential_updated_inputs, $user_id, $this->dbConnection);

        foreach ($potential_updated_inputs as $potential_version) {
            $old_version = $this->findInputById($potential_version->id, $old_inputs);

            if ($old_version != NULL) {
                if ($old_version->keey != $potential_version->key || $old_version->value != $potential_version->value) {
                    $update_inputs_query = "UPDATE attributes SET keey ='$potential_version->key', value = '$potential_version->value' WHERE id='$potential_version->id'";
                    $this->dbConnection->query($update_inputs_query);
                }
            }
        }
    }

    public function deleteRemovedInputs($inputs_array, $user_id)
    {
        $potential_updated_inputs = $this->getPotentialUpdated($inputs_array);
        $old_inputs = $this->getAllAttributes($user_id, $this->dbConnection);

        foreach ($old_inputs as $old_version) {
            $potential_version = $this->findInputById($old_version->id, $potential_updated_inputs);
            if ($potential_version == NULL) {
                $deleted_inputs_query = "DELETE FROM attributes WHERE id='$old_version->id' AND user_id = '$user_id'  ";
                $this->dbConnection->query($deleted_inputs_query);
            }
        }
    }


    public function submit($post)
    {
        // lokals mainigais, lai vieglak stradat
        $user_id = $_SESSION['user_id'];
        // all inputs submitted form
        $all_inputs_array = $this->mergeFormInput($post['id'], $post['keey'], $post['value'], $this->dbConnection);
        // inputs which have been newly added
        $this->deleteRemovedInputs($all_inputs_array, $user_id, $this->dbConnection);
        $this->storeNewInputs($all_inputs_array, $user_id, $this->dbConnection);
        $this->updateExistingInputs($all_inputs_array, $user_id, $this->dbConnection);
        $this->app->pushError("Changes saved successfully");
    }
}

?>