<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 17/07/2017
 * Time: 10:38 AM.
 */

namespace Greenter\Validator\Loader;

use Greenter\Validator\Metadata\LoaderMetadataInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Greenter\Validator\Constraint as MyAssert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class SaleDetailLoader implements LoaderMetadataInterface
{
    public function load(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraints('unidad', [
            new Assert\NotBlank(),
            new MyAssert\CodeUnit(),
        ]);
        $metadata->addPropertyConstraints('descripcion', [
            new Assert\NotBlank(),
            new Assert\Length(['max' => 250]),
        ]);
        $metadata->addPropertyConstraint('cantidad', new Assert\NotBlank());
        $metadata->addPropertyConstraint('codProducto', new Assert\Length(['max' => 30]));
        $metadata->addPropertyConstraint('codProdSunat', new Assert\Length(['max' => 20]));
        $metadata->addPropertyConstraint('mtoValorUnitario', new Assert\NotBlank());
        $metadata->addPropertyConstraint('igv', new Assert\NotBlank());
        $metadata->addPropertyConstraint('tipAfeIgv', new Assert\NotBlank());
        $metadata->addPropertyConstraint('mtoPrecioUnitario', new Assert\NotBlank());
        $metadata->addPropertyConstraint('mtoValorVenta', new Assert\NotBlank());
    }
}
