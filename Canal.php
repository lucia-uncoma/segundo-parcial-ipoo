<?php

class Canal {
    private static $ultimoId = 0; // variable estática compartida por todas las instancias para conocer ultimo id

    private $id;
    private $tipo;
    private $importe;
    private $esHD;

    public function __construct($tipo, $importe, $esHD) {
        $this->tipo = $tipo;
        $this->importe = $importe;
        $this->esHD = $esHD;
        self::$ultimoId++;            // incrementamos el ID que todas conocen
        $this->id = self::$ultimoId;  // lo asignamos al canal actual
    }
//--------------------------------------------------------------------------------------------------

public function getTipo() {
    return $this->tipo;
}

public function getImporte() {
    return $this->importe;
}

public function getEsHD() {
    return $this->esHD;
}

public function setTipo($tipo) {
    $this->tipo = $tipo;
}

public function setImporte($importe) {
    $this->importe = $importe;
}

public function setEsHD($esHD) {
    $this->esHD = $esHD;
}

//--------------------------------------------------------------------------------------------------
    public function __toString() {
        $hd = $this->esHD ? "HD" : "SD";
        return "Canal: {$this->tipo}, Importe: {$this->importe}, Calidad: {$hd}";
    }
    public function getId() {
        return $this->id;
    }
}

?>