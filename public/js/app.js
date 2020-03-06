//require('../bootstrap-typeahead');
/*** V1 ***/

$(function() {
    var input = $('#search-input');
    var suggestUrl = input.data('suggest');

    input.typeahead({
        minLength: 2, // will start autocomplete after 2 characters
        items: 5, // will display 5 suggestions
        highlighter: function (item) {
            var elem = this.reversed[item];
            var html = '<div class="typeahead">';

            if (elem.title) {
                html += '<div class="suggestion">' + elem.title + '</div>';
            }

            html += '</div>';

            return html;
        },
        source: function(query, process) {
            // "query" is the search string
            // "process" is a closure which must receive the suggestions list

            var $this = this;
            //alert(suggestUrl);
            $.ajax({
                url: suggestUrl,
                type: 'GET',
                dataType: 'JSON',
                data: {
                    q: query
                },
                success: function(data) {
                    //  "name" is the string to display in the suggestions

                    // this "reversed" array keep a temporarly relation between each suggestion and its data
                    var reversed = {};

                    // here we simply generate the suggestions list
                    var suggests = [];

                    $.each(data, function(id, elem) {
                        reversed[elem.title] = elem;
                        suggests.push(elem.title);
                    });

                    $this.reversed = reversed;

                    // displaying the suggestions
                    process(suggests);
                }
            })
        },
        // this method is called when a suggestion is selected
        updater: function(item) {
            // do whatever you want

            return item; // this return statement fills the input with the selected string
        },
        // this method determines which of the suggestions are valid. We are already doing all this server side, so here just
        // return "true"
        matcher: function() {
            return true;
        }
    });
});
 