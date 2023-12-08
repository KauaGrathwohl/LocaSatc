CKEDITOR.plugins.add( 'delimiter', {
    icons: 'delimiter',
    init: function( editor ) {
        editor.addCommand( 'insertDelimiter', {
            exec: function(){
                editor.insertHtml('<div class="break-text"></div>');
            }
        });
        editor.ui.addButton( 'Delimiter', {
            label: 'Inserir Quebra',
            command: 'insertDelimiter',
            toolbar: 'insert'
        });
    }
});