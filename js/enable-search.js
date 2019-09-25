// Find the search icon and replace the link with the call to the modal window
var enableSearch = function() {

     var el = document.querySelector('.clb-custom-search-icon').getElementsByTagName('a')[0];
     var search = '#site-search';

     el.href = search;
     el.title = 'Search this website';
     el.setAttribute("data-toggle", "modal");
}

enableSearch();
