let gridApi;

const gridOptions = {
    rowData: [],

    // Column Definitions: Defines the columns to be displayed.
    columnDefs: [
        { field: "Id" },
        { field: "State" },
        { field: "Status" },
        { field: "Duration" },
        { field: "Health" },
        { field: "Created" },
    ]
};





document.addEventListener('DOMContentLoaded', function(e) {
    fetch("/test/getContainerStats")
        .then((response) => response.json())
        .then((data) => gridApi.setGridOption('rowData', data));

    const myGridElement = document.querySelector('#myGrid');
    gridApi = agGrid.createGrid(myGridElement, gridOptions);
})
