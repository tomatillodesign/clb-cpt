




// This function will check all links within .entry-content, then set links to different sites to open in new tabs automatically
var  setLinkTargets = function() {

var  entryContent = document.querySelector( '.entry-content' ),
     atts = entryContent.attributes,
     thisSite = document.defaultView.location.origin,
     numLinks = entryContent.getElementsByTagName('a').length;

     for( var i = 0, max = numLinks; i < max; i++ ) {

          var  a = entryContent.getElementsByTagName('a')[i],
               linkSite = a.origin,
               link = a.getAttribute("href");

               //console.log(linkSite);

               if ( link.endsWith('pdf') || link.endsWith('PDF') ) {

                    a.setAttribute( 'target' , '_blank' );

               }

               else if( thisSite !== linkSite ) {

                    a.setAttribute( 'target' , '_blank' );

               } else {

                    a.setAttribute( 'target' , '_self' );

               }

     }

}

setLinkTargets();








// Fix for callouts that are purposely missing titles
var  fixMissingCalloutTitles = function() {

     var  calloutBoxes = document.querySelectorAll( '.clb-callout-area' ),
          atts = calloutBoxes.attributes,
          numBoxes = calloutBoxes.length;

     for( var i = 0, max = numBoxes; i < max; i++ ) {

          var calloutHeading = calloutBoxes[i].getElementsByTagName('h3');

          if( calloutHeading[0].innerHTML === '' ) {
               calloutHeading[0].style.display = 'none';
          }

     }

}

fixMissingCalloutTitles();





// This function will check all links on the page, incl header + footer, for '#subscribe' and will add the modal data markup
// Modal HTML markup should be added in clb-cpt.php, functions.php, etc.
var  setSubscribeModal = function() {

     //console.log('setSubscribeModal 12:10p');

var  fullContent = document.querySelector( '.site-container' ),
     atts = fullContent.attributes,
     numLinks = fullContent.getElementsByTagName('a').length;

     for( var i = 0, max = numLinks; i < max; i++ ) {

          var  a = fullContent.getElementsByTagName('a')[i],
               linkSite = a.origin,
               link = a.getAttribute("href");

               if( link === '#subscribe' ) {
                    a.removeAttribute("target");
                    //a.setAttribute("toggle", "modal");
                    a.dataset.toggle = "modal";
                    //data-toggle="modal"
                    console.log( a );
               }



     }

}

setSubscribeModal();
