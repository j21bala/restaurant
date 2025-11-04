<?php
class VisitasRecientesStack {
    private $stack;
    private $maxSize;

    public function __construct($maxSize = 5) {
        if (!isset($_SESSION['visitas_recientes'])) {
            $_SESSION['visitas_recientes'] = [];
        }
        $this->stack = &$_SESSION['visitas_recientes'];
        $this->maxSize = $maxSize;
    }

    public function push($item) {
        if ($this->peek() === $item) {
            return;
        }
        $this->remove($item);
        
        array_unshift($this->stack, $item);

        while ($this->size() > $this->maxSize) {
            array_pop($this->stack);
        }
    }

    public function pop() {
        return array_shift($this->stack);
    }

    public function peek() {
        return $this->isEmpty() ? null : $this->stack[0];
    }

    public function isEmpty() {
        return empty($this->stack);
    }

    public function size() {
        return count($this->stack);
    }
    
    public function getStack() {
        return $this->stack;
    }

    private function remove($item) {
        $index = array_search($item, $this->stack);
        if ($index !== false) {
            unset($this->stack[$index]);
            $this->stack = array_values($this->stack);
        }
    }
}
?>