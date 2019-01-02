<?php

require_once('Config.php');

/**
 * Class Attributes allows personal account to add, delete, update attributes
 */
class Attributes
{
    private $dbConnection;
    private $app;
    public $user_id;
    /**
     * $keey instead of $key, because mySql does not allows to use key
     * @var
     */
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

    /**
     * function get all parameters (id, key, value) and converts as array by new id to make easier to work with them
     * @param $id_array
     * @param $keey_array
     * @param $value_array
     * returns inputs as array
     * @return array
     */
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

    /**
     * this function goes through all inputs array, all empty ids will be new_inputs,
     * then foreach loop goes through new_inputs and if they not empty, they will be added in database
     * @param $inputs_array
     * @param $user_id
     */
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

    /**
     * this function goes through all_inputs_array and returns saved inputs whom have an id
     * @param $inputs_array
     * @return array
     */
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

    /**
     * this function returns all logged_in user attributes from database and shows in url
     * @return mixed
     */
    public function getAttributesByUserId()
    {
        $user_id = $_SESSION['user_id'];
        $attr_query = "SELECT * FROM attributes WHERE user_id ='$user_id' ";
        $attr_result = $this->dbConnection->query($attr_query);
        $attr_rows = $attr_result->fetch_all(MYSQLI_ASSOC);

        return $attr_rows;
    }

    /**
     * this function returns all logged_in user attributes from database
     * @param $user_id
     * @return mixed
     */
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

    /**
     * this function compares two ids
     * @param $id
     * @param $searchable_array
     * @return null
     */
    public function findInputById($id, $searchable_array)
    {
        foreach ($searchable_array as $item) {
            if ($item->id == $id) {
                return $item;
            }
        }
        return null;
    }

    /**
     * this function compares inputs from database and inputs from account page and if they not similar, then attributes will be updated
     * @param $inputs_array
     * @param $user_id
     */
    public function updateExistingInputs($inputs_array, $user_id)
    {
        $potential_updated_inputs = $this->getPotentialUpdated($inputs_array);
        $old_inputs = $this->getAllAttributes($user_id);

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

    /**
     * this function goes through all old_inputs ids and compares with potential_updated_inputs ids and if returns NULL, then it will be deleted from database
     * @param $inputs_array
     * @param $user_id
     */
    public function deleteRemovedInputs($inputs_array, $user_id)
    {
        $potential_updated_inputs = $this->getPotentialUpdated($inputs_array);
        $old_inputs = $this->getAllAttributes($user_id);

        foreach ($old_inputs as $old_version) {
            $potential_version = $this->findInputById($old_version->id, $potential_updated_inputs);
            if ($potential_version == NULL) {
                $deleted_inputs_query = "DELETE FROM attributes WHERE id='$old_version->id' AND user_id = '$user_id'  ";
                $this->dbConnection->query($deleted_inputs_query);
            }
        }
    }

    /**
     * function submit all changes
     * @param $post
     */
    public function submit($post)
    {
        $user_id = $_SESSION['user_id'];
        $all_inputs_array = $this->mergeFormInput($post['id'], $post['keey'], $post['value']);
        $this->deleteRemovedInputs($all_inputs_array, $user_id);
        $this->storeNewInputs($all_inputs_array, $user_id);
        $this->updateExistingInputs($all_inputs_array, $user_id);
        $this->app->pushInfo("Changes saved successfully");
    }
}

?>