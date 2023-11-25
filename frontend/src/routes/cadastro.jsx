import { useState } from "react";
import axios from "axios";

function CadastroUsuario() {
  const [usuario, setUsuario] = useState({
    nome: "",
    email: "",
    confirmarEmail: "",
    senha: "",
    confirmarSenha: "",
    celular: "",
  });
  const [carregando, setCarregando] = useState(false);
  const [isFormSubmitted, setIsFormSubmitted] = useState(false);

  const handleSubmit = async (event) => {
    event.preventDefault();
    setCarregando(true);

    try {
      if (usuario.email !== usuario.confirmarEmail) {
        throw new Error("Os campos de email e confirmar email não são iguais.");
      }

      if (usuario.senha !== usuario.confirmarSenha) {
        throw new Error("Os campos de senha e confirmar senha não são iguais.");
      }

      const data = JSON.stringify(usuario);
      const response = await axios.post(
        "http://localhost/backend-ABP-front/usuario/cadastrar",
        data,
        {}
      );
      console.log(response.data);
      setIsFormSubmitted(true);
      setUsuario({
        nome: "",
        email: "",
        confirmarEmail: "",
        senha: "",
        confirmarSenha: "",
        celular: "",
      });
    } catch (error) {
      console.error(error.message);
    }

    setCarregando(false);
  };

  const handleChange = (event) => {
    const { name, value } = event.target;
    setUsuario((prevUsuario) => ({ ...prevUsuario, [name]: value }));
  };

  return (
    <div className="container">
      <div className="bg-black-div/90 border border-white rounded-xl my-4 py-10 px-8">
        <form
          id="formUsuario"
          className="flex flex-col space-y-12"
          onSubmit={handleSubmit}
        >
          <input
            className="ml-5 bg-black-input text-white rounded px-4 py-3"
            placeholder="Nome"
            type="text"
            name="nome"
            value={usuario.nome}
            onChange={handleChange}
          />
          <input
            className="ml-5 bg-black-input text-white rounded px-4 py-3"
            placeholder="Celular"
            type="text"
            name="celular"
            value={usuario.celular}
            onChange={handleChange}
          />
          <input
            className="ml-5 bg-black-input text-white rounded px-4 py-3"
            placeholder="E-mail"
            type="email"
            name="email"
            value={usuario.email}
            onChange={handleChange}
          />
          <input
            className="ml-5 bg-black-input text-white rounded px-4 py-3"
            placeholder="Confirmar E-mail"
            type="email"
            name="confirmarEmail"
            value={usuario.confirmarEmail}
            onChange={handleChange}
          />
          <input
            className="ml-5 bg-black-input text-white rounded px-4 py-3"
            placeholder="Senha"
            type="password"
            name="senha"
            value={usuario.senha}
            onChange={handleChange}
          />
          <input
            className="ml-5 bg-black-input text-white rounded px-4 py-3"
            placeholder="Confirmar Senha"
            type="password"
            name="confirmarSenha"
            value={usuario.confirmarSenha}
            onChange={handleChange}
          />

          <button
            className="text-white flex w-1/4 mx-auto justify-center items-center bg-blue-high rounded p-5 transition-all hover:bg-blue-light"
            type="submit"
            disabled={carregando}
          >
            {carregando ? (
              <img
                src="../../public/svg/loading_white.svg"
                className="w-9 h-9"
              />
            ) : (
              "Cadastrar"
            )}
          </button>
          {isFormSubmitted && (
            <p className="text-white">Formulário enviado com sucesso!</p>
          )}
        </form>
      </div>
    </div>
  );
}
export default CadastroUsuario;
