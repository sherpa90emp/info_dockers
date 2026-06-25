let gridApi;

const gridOptions = {
    rowData: [],

    // Column Definitions: Defines the columns to be displayed.
    columnDefs: [
        { field: "Id", flex: 1,
        },
        { field: "State" },
        { field: "Status", flex: 2,},
        { field: "Duration" },
        { field: "Health" },
        { field: "Created", flex: 2,
        },
    ],

    pagination: true,
    paginationPageSize: 17,
    paginationPageSizeSelector: [10, 20],
};

document.addEventListener('DOMContentLoaded', function(e) {
    const myGridElement = document.querySelector('#myGrid');
    gridApi = agGrid.createGrid(myGridElement, gridOptions);

    fetch("/docker-stats/tabella")
        .then((response) => response.json())
        .then((data) => gridApi.setGridOption('rowData', data));
})
