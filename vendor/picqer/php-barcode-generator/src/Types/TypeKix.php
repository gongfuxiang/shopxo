<?php

namespace Picqer\Barcode\Types;

/*
 * RMS4CC - CBC - KIX
 * RMS4CC (Royal Mail 4-state Customer Code) - CBC (Customer Bar Code) - KIX (Klant index - Customer index)
 * RM4SCC is the name of the barcode symbology used by the Royal Mail for its Cleanmail service.
 * @param $kix (boolean) if true prints the KIX variation (doesn't use the start and end symbols, and the checksum)
 *     - in this case the house number must be sufficed with an X and placed at the end of the code.
 */

class TypeKix extends TypeRms4cc
{
    protected $kix = true;
}
