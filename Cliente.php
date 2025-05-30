<?php

class Cliente {
    // TODO: Revisar si guardo todos estos datos del cliente o solo INFO DE DOC dentro de contrato
    private $nombre;
    private $apellido;
    private $tipoDoc;
    private $nroDoc;

    public function __construct($nombre, $apellido, $tipoDoc, $nroDoc) {
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->tipoDoc = $tipoDoc;
        $this->nroDoc = $nroDoc;
    }

    public function getTipoDoc() {
        return $this->tipoDoc;
    }
    
    public function getNroDoc() {
        return $this->nroDoc;
    }
    public function __toString() {
        return "Cliente: {$this->nombre} {$this->apellido} (Tipo de Documento: {$this->tipoDoc}, Número de Documento: {$this->nroDoc})";
    }
}

?>