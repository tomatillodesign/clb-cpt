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

               console.log(linkSite);

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
