import { useState } from "react";
import axios from "axios";

function Login() {
  const [credenciais, setCredenciais] = useState({
    email: "",
    senha: "",
  });
  const [carregando, setCarregando] = useState(false);
  const [isFormSubmitted, setIsFormSubmitted] = useState(false);
  const [formError, setFormError] = useState("");

  const handleSubmit = async (event) => {
    event.preventDefault();
    setCarregando(true);

    try {
      if (!credenciais.email || !credenciais.senha) {
        throw new Error("Por favor, preencha todos os campos.");
      }

      const data = JSON.stringify(credenciais);
      const response = await axios.post(
        "http://localhost/backend-ABP-front/login",
        data,
        {}
      );
      console.log(response.data);
      setIsFormSubmitted(true);
      setFormError("");
    } catch (error) {
      console.error(error.message);
      setFormError("Credenciais inválidas. Por favor, tente novamente.");
    }

    setCarregando(false);
  };

  const handleChange = (event) => {
    const { name, value } = event.target;
    setCredenciais((prevCredenciais) => ({
      ...prevCredenciais,
      [name]: value,
    }));
  };

  return (
    <div className="container">
      <div className="bg-black-div/90 border border-white rounded-xl my-20 py-12 px-8">
        <form
          id="formLogin"
          className="flex flex-col space-y-12"
          onSubmit={handleSubmit}
        >
          <input
            className="ml-5 bg-black-input text-white rounded px-4 py-3"
            placeholder="E-mail"
            type="email"
            name="email"
            value={credenciais.email}
            onChange={handleChange}
          />
          <input
            className="ml-5 bg-black-input text-white rounded px-4 py-3"
            placeholder="Senha"
            type="password"
            name="senha"
            value={credenciais.senha}
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
              "Login"
            )}
          </button>
          {formError && <p className="text-white">{formError}</p>}
          {isFormSubmitted && <p className="text-white">Login bem-sucedido!</p>}
        </form>
      </div>
    </div>
  );
}

export default Login;
