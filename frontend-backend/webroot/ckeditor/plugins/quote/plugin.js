CKEDITOR.plugins.add( 'quote', {
    icons: 'quote',
    init: function( editor ) {
        editor.addCommand( 'insertQuote', new CKEDITOR.dialogCommand('quoteDialog'));
        editor.ui.addButton( 'Quote', {
            label: 'Inserir Quote',
            command: 'insertQuote',
            toolbar: 'insert'
        });
        CKEDITOR.dialog.add( 'quoteDialog', this.path + 'dialogs/quote.js' );
    }
});