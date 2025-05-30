<?php

class Contrato {
    private static $ultimoCodigo = 0; // variable estática compartida por todas las instancias para conocer ultimo id

    // protected porque ContratoWeb tiene que heredarlos
    protected $fechaInicio;
    protected $fechaVencimiento;
    protected $plan;        // referencia a un objeto Plan
    protected $estado;      // al día, moroso, suspendido, finalizado
    protected $costo;
    protected $renueva;     // true o false
    protected $objCliente;     // referencia a un objeto Cliente
    protected $codigo; // agrego el atributo en plan para poder identificarlo

    public function __construct($fechaInicio, $fechaVencimiento, $plan, $estado, $costo, $renueva, $objCliente) {
        $this->fechaInicio = $fechaInicio;
        $this->fechaVencimiento = $fechaVencimiento;
        $this->plan = $plan;
        $this->estado = $estado;
        $this->costo = $costo;
        $this->renueva = $renueva;
        $this->objCliente = $objCliente;
        self::$ultimoCodigo++;            // incrementamos el ID que todas conocen
        $this->codigo = self::$ultimoCodigo;  // lo asignamos al canal actual
    }

    public function getCliente() {
        return $this->objCliente;
    }
    public function getEstado() {
        return $this->estado;
    }
    public function getPlan() {
        return $this->plan;
    }
    
    public function setEstado($estado) {
        $this->estado = $estado;
    }
    public function getCodigo() {
        return $this->codigo; 
    }
    
    public function getFechaVencimiento() {
        return $this->fechaVencimiento;
    }
    
    public function setFechaVencimiento($fecha) {
        $this->fechaVencimiento = $fecha;
    }
    
    public function setCosto($costo) {
        $this->costo = $costo;
    }

    // Método estático que recibe un contrato y calcula días vencidos
    // Es estático para funcionar con cualquier objeto de Contrato y
    // poder usarlo en cualquier instancia
    public static function diasContratoVencido(Contrato $contrato) {
        $fechaHoy = new DateTime();
        $fechaVenc = new DateTime($contrato->fechaVencimiento);
        $tiempoVencido = 0;
        if ($fechaHoy > $fechaVenc) {
            $intervalo = $fechaHoy->diff($fechaVenc);
            $tiempoVencido = $intervalo->days;
        }

        return $tiempoVencido;
    }
    
      // Método para actualizar el estado del contrato
      public function actualizarEstadoContrato() {
        $diasVencidos = self::diasContratoVencido($this);

        if ($this->estado === 'finalizado') {
            // finalizado no puede cambiar el estado
            return;
        }

        if ($diasVencidos === 0) {
            // todo perfecto
            $this->estado = 'al día';
        } elseif ($diasVencidos > 0 && $diasVencidos <= 30) {
            // vencido pero menos de 30 días
            $this->estado = 'moroso';
        } else {
            // Más de 30 días vencido
            $this->estado = 'suspendido';
        }
    }
    // en Contrato se calcula sin descuento porque eso es de ContratoWeb
    public function calcularImporte() {
        // las funciones de las clases Plan y Canal se ven bien en el repo
        // plan es un objeto de la clase y dentro tiene canales que sería 
        // un array de objetos de la clase Canal
        $importePlan = $this->plan->getImporte(); // importe base del plan
        $canales = $this->plan->getCanales();    // array de objetos Canal
        
        $importeCanales = 0;
        foreach ($canales as $canal) {
            $importeCanales += $canal->getImporte();
        }

        $importeFinal = $importePlan + $importeCanales;

        return $importeFinal;
    }

    public function __toString() {

        $cadena = "CONTRATO #{$this->codigo}\n";
        $cadena .= "Cliente: " . $this->objCliente->__toString() . "\n";
        $cadena .= "Plan: " . $this->plan->__toString() . "\n";
        $cadena .= "Fecha de inicio: " . $this->fechaInicio . "\n";
        $cadena .= "Fecha de vencimiento: " . $this->fechaVencimiento . "\n";
        $cadena .= "Estado: " . $this->estado . "\n";
        $cadena .= "Costo base registrado: $" . ($this->costo) . "\n";
        $cadena .= "Renovación automática: " . ($this->renueva ? "Sí" : "No") . "\n";
    
        return $cadena;
    }
}

?>