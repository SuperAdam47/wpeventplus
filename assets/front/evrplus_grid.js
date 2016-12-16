jQuery(function ($) {
    var oEvrGrid = $('#evr-grid');
    oEvrGrid.mediaBoxes({
        filterContainer: '#evr-filter',
        search: '#evr-search',
        boxesToLoadStart: oEvrGrid.attr('data-boxesToLoadStart'),
        boxesToLoad: oEvrGrid.attr('data-boxesToLoad'),
        horizontalSpaceBetweenBoxes: 20,
        verticalSpaceBetweenBoxes: 20
    });
});