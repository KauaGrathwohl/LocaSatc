CKEDITOR.plugins.add("materiacompleta", {
    icons: "materiacompleta",
    init: function(editor) {
        editor.addCommand("insertMateriaCompleta", {
            exec: function() {
                editor.insertHtml(
                    '<div class="bt-materia-completa-tribuna"><a href="http://atribuna.4oito.com.br/impresso" rel="external" title="Leia esta matéria completa em A Tribuna">Matéria Completa em Jornal A Tribuna</a></div>'
                );
            }
        });
        editor.ui.addButton("MateriaCompleta", {
            label: "Inserir Botão: Materia Completa A Tribuna",
            command: "insertMateriaCompleta",
            toolbar: "insert"
        });
    }
});
