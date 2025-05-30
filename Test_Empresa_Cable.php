<?php
include 'EmpresaCable.php';
include 'Canal.php';
include 'Plan.php';
include 'Cliente.php';
include 'Contrato.php';
include 'ContratoWeb.php';

//--------------------------------------------------------------------------------------------------

$empresa = new EmpresaCable();

$canal1 = new Canal("noticias", 200, true);
$canal2 = new Canal("películas", 300, false);
$canal3 = new Canal("deportivo", 250, true);

$plan1 = new Plan("111", [$canal1, $canal2], 1500);
$plan2 = new Plan("222", [$canal1, $canal3], 1800);

$empresa->incorporarPlan($plan1);
$empresa->incorporarPlan($plan2);

$cliente = new Cliente("Lucía", "Robertazzi", "DNI", "44555256");

//--------------------------------------------------------------------------------------------------

$fechaInicio = new DateTime("2025-05-01");
$fechaVencimiento = new DateTime("2025-06-01");

$contrato1 = new Contrato($fechaInicio, $fechaVencimiento, $plan1, "activo", 2000, true, $cliente);
$contrato2 = new ContratoWeb($fechaInicio, $fechaVencimiento, $plan2, "activo", 2500, true, $cliente, 10);
$contrato3 = new ContratoWeb($fechaInicio, $fechaVencimiento, $plan1, "activo", 2200, true, $cliente, 15);

echo "Contrato 1 (Empresa): $" . $contrato1->calcularImporte() . "\n";
echo "Contrato 2 (Web 10%): $" . $contrato2->calcularImporte() . "\n";
echo "Contrato 3 (Web 15%): $" . $contrato3->calcularImporte() . "\n";

//--------------------------------------------------------------------------------------------------

$hoy = new DateTime();
$fechaVencimiento = (clone $hoy)->modify('+30 days');

$contratoEmpresa = $empresa->incorporarContrato($plan1, $cliente, $hoy, $fechaVencimiento, false);
$contratoWeb = $empresa->incorporarContrato($plan2, $cliente, $hoy, $fechaVencimiento, true);

$empresa->pagarContrato($contratoEmpresa);
$empresa->pagarContrato($contratoWeb);

$importe = $empresa->retornarPromImporteContratos("111");
echo "Importe promedio contratos con código 111: $" . $importe . "\n";

//--------------------------------------------------------------------------------------------------

?>
