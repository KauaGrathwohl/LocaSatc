CKEDITOR.plugins.add( 'banner', {
    icons: 'banner',
    init: function( editor ) {
        editor.addCommand( 'insertBanner', {
            exec: function(){
                editor.insertHtml('<div class="news-full-banner"><span class="banner-text-placeholder">Banner</span></div>');
            }
        });
        editor.ui.addButton( 'Banner', {
            label: 'Inserir Banner',
            command: 'insertBanner',
            toolbar: 'insert'
        });
    }
});