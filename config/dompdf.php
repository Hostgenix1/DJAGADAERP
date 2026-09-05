<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default PDF Font
    |--------------------------------------------------------------------------
    |
    | All DJAGADA document PDFs (invoice, quote, supplier bill, purchase order)
    | use the Lato family, matching the BHANTAL reference invoice typography.
    | Run `php artisan pdf:install-fonts` once after deploy to register the
    | Lato faces (Medium as normal, Bold as bold) with dompdf.
    |
    */
    'default_font' => 'Lato',
];
