jQuery(function ($) {
    var oEvrGrid = $('#evr-grid');
    oEvrGrid.mediaBoxes({
        filterContainer: '.eventplus-grid-filter',
        search: '#evr-search',
        boxesToLoadStart: oEvrGrid.attr('data-boxesToLoadStart'),
        boxesToLoad: oEvrGrid.attr('data-boxesToLoad'),
        horizontalSpaceBetweenBoxes: 20,
        verticalSpaceBetweenBoxes: 20,
        LoadingWord: EvrGrid.LoadingWord,
        loadMoreWord: EvrGrid.loadMoreWord,
        noMoreEntriesWord: EvrGrid.noMoreEntriesWord,
        columnWidth: 250
    });
});