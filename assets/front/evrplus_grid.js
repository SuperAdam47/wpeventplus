(function ($) {
'use strict'; 
$(document).ready(function () {

	$( '.evr-grid-container' ).each( function() {
		var oEvrGrid = $( this );
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
			columnWidth: oEvrGrid.attr('data-boxesWidth'),
		} );
	} );
});
}(jQuery));