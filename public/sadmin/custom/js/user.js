(function ($) {
    "use strict";

    $("#userTable").DataTable({
        pageLength: 10,
        ordering: false,
        serverSide: true,
        processing: true,
        searching: false,
        responsive: true,
        ajax: $('#userTable-route').val(),
        language: {
            paginate: {
                previous: "<i class='fa-solid fa-angles-left'></i>",
                next: "<i class='fa-solid fa-angles-right'></i>",
            },
            searchPlaceholder: "Search pending event",
            search: "<span class='searchIcon'><i class='fa-solid fa-magnifying-glass'></i></span>",
        },
        dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
        columns: [
            {"data": "name", "name": "name",responsivePriority:1},
            {"data": "email", "name": "email"},
            {"data": "created_at", "name": "created_at"},
            {"data": "country", "name": "country"},
            {"data": "status", "name": "status"},
            {"data": "action", "name": "action"},
        ],
    });

    // activityDataTable js
    $('#activityDataTable').DataTable({
        pageLength: 25,
        ordering: false,
        serverSide: true,
        processing: true,
        searing: true,
        responsive:true,
        ajax: $('#user-activity-route').val(),
        language: {
            paginate: {
                previous: "<i class='fa-solid fa-angles-left'></i>",
                next: "<i class='fa-solid fa-angles-right'></i>",
            },
            searchPlaceholder: "Search pending event",
            search: "<span class='searchIcon'><i class='fa-solid fa-magnifying-glass'></i></span>",
        },
        dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
        columns: [
            {"data": "action"},
            {"data": "source"},
            {"data": "ip_address"},
            {"data": "location"},
            {"data": "created_at"}
        ]
    });

})(jQuery)
