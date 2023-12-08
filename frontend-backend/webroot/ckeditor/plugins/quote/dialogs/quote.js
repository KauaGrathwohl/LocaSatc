CKEDITOR.dialog.add( 'quoteDialog', function ( editor ) {
	return {
		title: 'Quote',
		minWidth: 400,
		minHeight: 200,
		contents: [
			{
				id: 'tab-basic',
				label: 'Basic Settings',
				elements: [
					{
						type: 'text',
						id: 'titulo',
						label: 'Título'
					},
					{
						type: 'textarea',
						id: 'quote',
						label: 'Texto',
						validate: CKEDITOR.dialog.validate.notEmpty( "O campo 'Texto' é obrigatório." )
					},
					{
						type: 'text',
						id: 'autor',
						label: 'Autor'
					}
				]
			}
		],
		onOk: function() {

			var dialog = this,
				textTitulo = dialog.getValueOf( 'tab-basic', 'titulo' ),
				textQuote = dialog.getValueOf( 'tab-basic', 'quote' ),
				textAutor = dialog.getValueOf( 'tab-basic', 'autor' );

			var divContainer = editor.document.createElement( 'div' );
			divContainer.addClass( 'quote-box' );
			
			if(textTitulo.trim() !== '')
			{
				var divContainerTop = editor.document.createElement( 'div' );
				divContainerTop.addClass( 'quote-titulo' );
				divContainerTop.setText( textTitulo );
				divContainer.append( divContainerTop );
			}
			
			var divContainerMiddle = editor.document.createElement( 'div' );
			divContainerMiddle.addClass( 'quote-content' );
			divContainerMiddle.setText( textQuote );
			divContainer.append( divContainerMiddle );
			
			if(textAutor.trim() !== '')
			{
				var divContainerBottom = editor.document.createElement( 'div' );
				divContainerBottom.addClass( 'quote-autor' );
				divContainerBottom.setText( textAutor );
				divContainer.append( divContainerBottom );
			}
			
			editor.insertElement( divContainer );
		}
	};
});