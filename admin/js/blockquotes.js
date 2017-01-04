/*
 * Adds a new custom blockquote button.
 * If text is selected, the button will toggle blockquote markup around the selection.
 * If no text is selected, a pop-up will display options for quote, citation, citation link.
 * Inspired by devinsays - blockquotes.js
 * Modified version - BS3 Grid Builder Edition
 *
 * @link https://github.com/devinsays/better-blockquotes
 * @link https://wordpress.org/plugins/better-blockquotes/
 * @since 1.0
 */

(function() {
    tinymce.PluginManager.add( 'bs3_grid_builder_better_blockquote', function( editor, url ) {

		editor.addButton( 'bs3_grid_builder_better_blockquote', {
            title: bs3_grid_builder_better_blockquotes.add_blockquote,
            type: 'button',
            icon: 'blockquote',
            onclick: function() {
				// If text is selected, toggle blockquote markup
	            if ( editor.selection.getContent() ) {
					editor.formatter.toggle('blockquote');
				// If no text is selected, display pop-up
	            } else {

					// Standard fields to display in blockquote pop-up
		            var body = [
					    {
					        type: 'textbox',
					        name: 'quote',
					        label: bs3_grid_builder_better_blockquotes.quote,
					        multiline: true,
					        minWidth: 300,
							minHeight: 100
					    },
						{
					        type: 'textbox',
					        name: 'cite',
					        label: bs3_grid_builder_better_blockquotes.citation,
					    },
						{
					        type: 'textbox',
					        name: 'link',
					        label: bs3_grid_builder_better_blockquotes.citation_link,
					    },
					];
					editor.windowManager.open({
					    title: bs3_grid_builder_better_blockquotes.blockquote,
					    body: body,
					    onsubmit: function( e ) {
						    var blockquote = '';
						    var cite = '';

							if ( e.data.link && e.data.cite ) {
								cite = '<cite><a href="' + e.data.link + '">' + e.data.cite + '</a></cite>';
	              			} else if ( !e.data.link && e.data.cite ) {
				  				cite = '<cite>' + e.data.cite + '</cite>';
	              			}

	  						if ( e.data.quote ) {
		  						if ( e.data.class ) {
			  						blockquote += '<blockquote class="' + e.data.class + '">';
		  						} else {
			  						blockquote += '<blockquote>';
		  						}
	  							blockquote += e.data.quote;
	  							blockquote += cite;
	  							blockquote += '</blockquote>';
						    }

					        editor.insertContent(blockquote);
					    }
					});
				}
			}
        });
    });
})();