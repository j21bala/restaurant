<?php
class Grafo {
    private $nodos = [];

    public function agregarNodo($nombre) {
        if (!isset($this->nodos[$nombre])) {
            $this->nodos[$nombre] = [];
        }
    }

    public function agregarArista($origen, $destino, $peso = 1) {
        if (isset($this->nodos[$origen]) && isset($this->nodos[$destino])) {
            $this->nodos[$origen][$destino] = $peso;
        }
    }

    public function obtenerNodos() {
        return array_keys($this->nodos);
    }

    public function obtenerAristas($nodo) {
        return isset($this->nodos[$nodo]) ? $this->nodos[$nodo] : null;
    }

    public function encontrarCaminoMasCorto($inicio, $fin) {
        $distancias = [];
        $anterior = [];
        $cola_prioridad = new SplPriorityQueue();

        foreach (array_keys($this->nodos) as $nodo) {
            $distancias[$nodo] = INF;
            $anterior[$nodo] = null;
        }

        if (!isset($this->nodos[$inicio])) {
            return null; // Nodo de inicio no existe
        }

        $distancias[$inicio] = 0;
        $cola_prioridad->insert($inicio, 0);

        while (!$cola_prioridad->isEmpty()) {
            $u = $cola_prioridad->extract();

            if ($u === $fin) {
                $camino = [];
                $temp = $fin;
                while (isset($anterior[$temp])) {
                    array_unshift($camino, $temp);
                    $temp = $anterior[$temp];
                }
                if ($temp === $inicio) {
                    array_unshift($camino, $inicio);
                    return $camino;
                }
                return null; // No se pudo construir el camino
            }

            if (!isset($this->nodos[$u])) continue;

            foreach ($this->nodos[$u] as $vecino => $peso) {
                if (!isset($distancias[$vecino])) continue;
                
                $alt = $distancias[$u] + $peso;
                if ($alt < $distancias[$vecino]) {
                    $distancias[$vecino] = $alt;
                    $anterior[$vecino] = $u;
                    // Usamos negativo porque SplPriorityQueue es un max-heap
                    $cola_prioridad->insert($vecino, -$alt);
                }
            }
        }
        return null; // No se encontró camino
    }

    public function imprimirGrafo() {
        foreach ($this->nodos as $nodo => $aristas) {
            echo "$nodo -> ";
            if (empty($aristas)) {
                echo "Sin aristas salientes\n";
            } else {
                $aristasStr = [];
                foreach ($aristas as $destino => $peso) {
                    $aristasStr[] = "$destino (peso: $peso)";
                }
                echo implode(", ", $aristasStr) . "\n";
            }
        }
    }
}
?>