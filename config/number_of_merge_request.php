<?php

return [
    "onoffice_create_contact_request" => 50,
    "flowfact_limit_contact_per_page" => 100, // get contact data
    "onoffice_create_estate_request" => 50,
    "flowfact_limit_estate_per_page" => 100, // get estate data

    "check_contact_exist_request" => 50, // limit request check contact exist per time
    "flowfact_send_async_request" => 5, // number of async request get data in job
    "flowfact_limit_request_get_search_criteria" => 1, // number of async request get search criteria data in job

    "limit_contact_per_activity_subjob" => 10,
    "limit_contact_per_note_subjob" => 10,
    "limit_contact_per_upload_file_subjob" => 10,
    "limit_contact_per_search_criteria_subjob" => 10,

    "onoffice_limit_contact_file_per_page" => 1, // number of files upload per job

    "limit_contact_activity_per_page" => 100, // number of contact activities get from FF and upload to OO per page
    "limit_estate_activity_per_page" => 100, // number of estate activities get from FF and upload to OO per page

    "limit_contact_search_criteria_per_page" => 50, // number of contact searchCriteria get from FF and import to OO per page
];
