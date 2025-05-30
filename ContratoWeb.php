<?php

class ContratoWeb extends Contrato {

    // descuento sólo en ContratoWeb
    private $descuento; 

    public function __construct($fechaInicio, $fechaVencimiento, $plan, $estado, $costo, $renueva, $objCliente, $descuento = 10) {
        parent::__construct($fechaInicio, $fechaVencimiento, $plan, $estado, $costo, $renueva, $objCliente);
        $this->descuento = $descuento;
    }
     // Getter y setter 
    public function getDescuento() {
        return $this->descuento;
}

public function setDescuento($nuevoDescuento) {
    $this->descuento = $nuevoDescuento;
}


    public function calcularImporte() {
        // Calculo con lo que hereda de Contrato
        $importeBase = parent::calcularImporte();

        // Se aplica el descuento
        $descuentoDecimal = $this->descuento / 100;
        $importeConDescuento = $importeBase * (1 - $descuentoDecimal);

        return $importeConDescuento;
    }


    public function __toString() {
        $cadena = parent::__toString(); // Uso el toString que viene de Contrato
        $cadena .= "\nDescuento: " . $this->descuento . "%";
        return $cadena;
    }

}

?>