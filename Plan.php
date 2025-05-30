<?php

class Plan {
    private $codigo;
    private $canales;  // array de objetos Canal
    private $importe;
    private $MG;

    public function __construct($codigo, $canales, $importe, $MG = 100) {
        $this->codigo = $codigo;
        $this->canales = $canales;
        $this->importe = $importe;
        $this->MG = $MG;
    }
//--------------------------------------------------------------------------------------------------

    public function getImporte() {
        return $this->importe;
    }

    public function getCanales() {
        return $this->canales;
    }
    public function getCodigo() {
        return $this->codigo;
    }
    public function getCanales() {
        return $this->canales;
    }
    public function getMG() {
        return $this->MG;
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function setCanales($canales) {
        $this->canales = $canales;
    }

    public function setImporte($importe) {
        $this->importe = $importe;
    }

    public function setMG($MG) {
        $this->MG = $MG;
    }

//--------------------------------------------------------------------------------------------------

    public function __toString() {
        $cadena = "Plan Código: {$this->codigo}, Importe: {$this->importe}, MG: {$this->MG}\n";
        $cadena .= "Canales:\n";
        foreach ($this->canales as $canal) {
            $cadena .= "- " . $canal . "\n";
        }
        return $cadena;
    }
}

?>
