<?php

return [
    'tables' => [
        'contact_types' => 'ipro_contact_types',
        'sources'       => 'ipro_sources',
        'booking_rules' => 'ipro_booking_rules',
        'booking_tags'  => 'ipro_booking_tags',
        'locations'     => 'ipro_locations',
        'attributes'    => 'ipro_attributes',

        'contacts'       => 'ipro_contacts',
        'properties'     => 'ipro_properties',
        'bookings'       => 'ipro_bookings',
        'blockouts'      => 'ipro_blockouts',
        'availabilities' => 'ipro_availabilities',
        'custom_rates'   => 'ipro_custom_rates',
    ],

    'number_format' => [
        'decimals'            => 2,
        'decimal_separator'   => '.',
        'thousands_separator' => ',',
    ],

    'date_format' => [
        'display'            => 'd/m/y',
    ],
];
