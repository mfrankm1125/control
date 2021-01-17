$(document).ready(function(){
    //do some cleaning on load
    sortCells = $('td[data-sorting], th[data-sorting]');
    sortCells.each(function(){
        var first = $(this).text().trim().charAt(0);
        if (first == '@'){
            var oldHTML = $(this).html();
            var newHTML = oldHTML.replace('@', '');
            $(this).html(newHTML);
        } 
    });
    function sortingTable(node, pos, ordBy){
        var rows = node.children('tbody').children('tr').get();

        rows.sort(function(a, b) {

        var X = $(a).children('td').eq(pos).text().toUpperCase();
        var Y = $(b).children('td').eq(pos).text().toUpperCase();
        
        if (numFormat != '.,'){
            //we are dealing with a different locale and we need to do some 
            //transformation in numbers
            X = convert2number(X);
            Y = convert2number(Y);
        } else {
            X = X.replace(/,/g, '').replace(/ /g, '');
            Y = Y.replace(/,/g, '').replace(/ /g, '');
        }
      
        if (!String(X).match(/^[0-9\-]+/) || !String(Y).match(/^[0-9\-]+/)){
            if(X < Y) {
                return -1 * ordBy;
            }
            if(X > Y) {
                return 1 * ordBy;
            }
            return 0;
        } else {
            return (Y-X) * ordBy;
        }
      });

      $.each(rows, function(index, row) {
        node.children('tbody').append(row);
      });
    }
    
    function removeTableCellDecoration() {
        $("td[data-sorting], th[data-sorting]").removeClass('sortDecorator');    
    }

    $("td[data-sorting], th[data-sorting]").click(function(){
        removeTableCellDecoration();
        $(this).addClass('sortDecorator');
        //get desired ordering
        var ordering = $(this).attr('data-sortOrdering');
        if (ordering == 'ASC'){
            var ordBy = -1;
            $(this).attr('data-sortOrdering', 'DESC');
        } else {
            var ordBy = 1;
            $(this).attr('data-sortOrdering', 'ASC');
        }
        var cellPosition = $(this).prevAll().length;
        var table = $(this).parents('table:first');
        sortingTable(table, cellPosition, ordBy);
    });  
    
    function convert2number(str) {
        str = str.trim();
        if (str.match(/^[0-9\-]+/)) {
            //we have to take special care when the thousand separators are '.'
            result_0 = str.replace(/\./g, '_@_');
            result_1 = result_0.replace(numFormat[0], '.');
            result = result_1.replace(/_@_/g, '');
            result .replace(/ /g, '');
            return result;
        } else {
            return str;
        }
        
    }
    
});


