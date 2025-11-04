<?php
class Node {
    public $id;
    public $data;
    public $left;
    public $right;

    public function __construct($id, $data) {
        $this->id = $id;
        $this->data = $data;
        $this->left = null;
        $this->right = null;
    }
}
?>