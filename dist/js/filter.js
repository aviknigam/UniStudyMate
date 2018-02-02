// filter.js is used in /textbooks/index to filter through the available textbooks on sale list

var $rows = $('#table tr');

$('#search').keyup(function() {
var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

$rows.show().filter(function() {
    var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
    return !~text.indexOf(val);

}).hide();
});

