<?php

class EmpresaCable {
    private $clientes;   // array de objetos de la clase Cliente
    private $planes;     // array de objetos de la clase Plan
    private $canales;    // array de objetos de la clase Canal
    private $contratos;  // array de objetos de la clase Contrato

    public function __construct() {
        $this->clientes = [];
        $this->planes = [];
        $this->canales = [];
        $this->contratos = [];
    }

    public function incorporarContrato($plan, $objCliente, $fechaInicio, $fechaVencimiento, $esWeb) {
        $i = 0;
    $n = count($this->contratos);
    $contratoActivoEncontrado = false;

    while ($i < $n && !$contratoActivoEncontrado) {
        $contrato = $this->contratos[$i];
        $cliente = $contrato->getCliente();

        if (
            $cliente->getTipoDoc() === $objCliente->getTipoDoc() &&
            $cliente->getNroDoc() === $objCliente->getNroDoc() &&
            $contrato->getEstado() !== 'finalizado'
        ) {
            $contrato->setEstado('finalizado');
            $contratoActivoEncontrado = true;
        }

        $i++;
    }
    
        // Creo el nuevo contrato según sea web o no
        if ($esWeb) {
            $nuevoContrato = new ContratoWeb(
                $fechaInicio,
                $fechaVencimiento,
                $plan,
                'al día',    // estado inicial
                0,          // costo inicial (podés calcular luego)
                true,
                $objCliente
            );
        } else {
            $nuevoContrato = new Contrato(
                $fechaInicio,
                $fechaVencimiento,
                $plan,
                'al día',
                0,
                true,
                $objCliente
            );
        }
        // Después de crear la instancia calculo su costo
        $nuevoContrato->setCosto($nuevoContrato->calcularImporte());
        // Agrego el nuevo contrato a la lista
        $this->contratos[] = $nuevoContrato;
    }

    public function retornarPromImporteContratos($codigoPlan) {
        $sumaImportes = 0;
        $cantidad = 0;
        $promedio = 0;
    
        foreach ($this->contratos as $contrato) {
            $plan = $contrato->getPlan();
            if ($plan->getCodigo() === $codigoPlan) {
                $sumaImportes += $contrato->calcularImporte();
                $cantidad++;
            }
        }
    
        if ($cantidad > 0) {
            $promedio = $sumaImportes / $cantidad;
        }
        return $promedio;
    }

    public function buscarContrato($tipoDoc, $nroDoc) {
        $contratoCliente = null;
        $i = 0;
        $n = count($this->contratos);
    
        while ($i < $n && $contratoCliente === null) {
            $cliente = $this->contratos[$i]->getCliente();
    
            if (
                $cliente->getTipoDoc() === $tipoDoc &&
                $cliente->getNroDoc() === $nroDoc
            ) {
                $contratoCliente = $this->contratos[$i];
            }
    
            $i++;
        }
    
        return $contratoCliente;
    }
    public function pagarContrato($codigoContrato) {
        $importeFinal = 0;
        $contratoEncontrado = null;
    
        $i = 0;
        $n = count($this->contratos);
    
        while ($i < $n && $contratoEncontrado === null) {
            $contrato = $this->contratos[$i];
            if ($contrato->getCodigo() === $codigoContrato) {
                $contratoEncontrado = $contrato;
            }
            $i++;
        }
    
        // Si está finalizado o no existe, no se puede pagar
        if ($contratoEncontrado !== null && $contratoEncontrado->getEstado() !== 'finalizado') {

        $contratoEncontrado->actualizarEstadoContrato();
    
        $estado = $contratoEncontrado->getEstado();
    
        // Calcular importe base sin multa todavía
        $importeBase = $contratoEncontrado->calcularImporte();
    
        // Calcular días vencidos para multa en caso de que se necesite
        $diasVencidos = Contrato::diasContratoVencido($contratoEncontrado);
    
        switch ($estado) {
            case 'al día':
                $fechaVenc = new DateTime($contratoEncontrado->getFechaVencimiento());
                $fechaVenc->modify('+1 month');
                $contratoEncontrado->setFechaVencimiento($fechaVenc->format('Y-m-d'));
    
                $importeFinal = $importeBase;
                break;
    
            case 'moroso':
                // Multa 10% * días de demora en pagar
                $multa = $importeBase * 0.10 * $diasVencidos;
    
                $importeFinal = $importeBase + $multa;
    
                // Actualizo estado a 'al día' y renuevo 1 mes
                $contratoEncontrado->setEstado('al día');
    
                $fechaVenc = new DateTime($contratoEncontrado->getFechaVencimiento());
                $fechaVenc->modify('+1 month');
                $contratoEncontrado->setFechaVencimiento($fechaVenc->format('Y-m-d'));
    
                break;
    
            case 'suspendido':
                // igual que el estado anterior
                $multa = $importeBase * 0.10 * $diasVencidos;
                $importeFinal = $importeBase + $multa;
    
                // No se renueva ni cambia estado
                break;
    
            default:
                // Por si no entra en ningún case
                $importeFinal = 0;
                break;
        }
    
        }
    
        
        return $importeFinal;
    }

    public function incorporarPlan($nuevoPlan) {
        $canalesNuevo = $nuevoPlan->getCanales(); // array de objetos Canal
        $idsNuevo = [];
    
        foreach ($canalesNuevo as $canal) {
            $idsNuevo[] = $canal->getId(); // usamos el identificador único
        }
    
        sort($idsNuevo); // ordenamos para comparar sin importar el orden original
        $mgNuevo = $nuevoPlan->getMG();
    
        $existe = false;
        $i = 0;
        $n = count($this->planes);
    
        while ($i < $n && !$existe) {
            $planExistente = $this->planes[$i];
            $canalesExistente = $planExistente->getCanales();
            $idsExistente = [];
    
            foreach ($canalesExistente as $canal) {
                $idsExistente[] = $canal->getId();
            }
    
            sort($idsExistente);
    
            if ($idsExistente === $idsNuevo && $planExistente->getMG() === $mgNuevo) {
                $existe = true;
            }
    
            $i++;
        }
    
        if (!$existe) {
            $this->planes[] = $nuevoPlan;
        }
    }
}

?>