<?php

namespace App\DataAccessLayer\EBoekHouden\Enum;

enum VatCodeEnum: string
{
    /**
     * 21% btw op verkopen.
     */
    case HOOG_VERK_21 = 'HOOG_VERK_21';

    /**
     * 9% btw op verkopen.
     */
    case LAAG_VERK_9 = 'LAAG_VERK_9';

    /**
     * BTW verlegd 21% op verkopen.
     */
    case VERL_VERK = 'VERL_VERK';

    /**
     * BTW verlegd 9% op verkopen.
     */
    case VERL_VERK_L9 = 'VERL_VERK_L9';

    /**
     * Afwijkende BTW op verkopen, gebruik VAT amount field.
     */
    case AFW_VERK = 'AFW_VERK';

    /**
     * Verkopen naar buiten de EU, 0% btw.
     */
    case BU_EU_VERK = 'BU_EU_VERK';

    /**
     * Verkopen van goederen naar EU-landen, 0% btw.
     */
    case BI_EU_VERK = 'BI_EU_VERK';

    /**
     * Verkopen van diensten naar EU-landen, 0% btw.
     */
    case BI_EU_VERK_D = 'BI_EU_VERK_D';

    /**
     * Afstand verkopen binnen de EU, 0% btw.
     */
    case AFST_VERK = 'AFST_VERK';

    /**
     * 9% btw op inkopen.
     */
    case LAAG_INK_9 = 'LAAG_INK_9';

    /**
     * 21% btw op inkopen.
     */
    case HOOG_INK_21 = 'HOOG_INK_21';

    /**
     * BTW verlegd 21% op inkopen.
     */
    case VERL_INK = 'VERL_INK';

    /**
     * Afwijkende BTW op inkopen, gebruik VAT amount field.
     */
    case AFW = 'AFW';

    /**
     * Inkopen van buiten de EU, 0% btw.
     */
    case BU_EU_INK = 'BU_EU_INK';

    /**
     * Inkopen van goederen of diensten uit EU-landen, 0% btw.
     */
    case BI_EU_INK = 'BI_EU_INK';

    public function getPercentage(): int
    {
        return match ($this) {
            self::HOOG_VERK_21, self::HOOG_INK_21, self::VERL_VERK => 21,
            self::LAAG_VERK_9, self::LAAG_INK_9, self::VERL_VERK_L9 => 9,
            default => 0,
        };
    }
}
