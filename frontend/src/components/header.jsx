import { Link } from "react-router-dom";

export default function Header() {
  return (
    <div className="App">
      <header className="bg-black-div/80 w-full">
        <div className="container">
          <div className="flex w-full justify-between uppercase items-center space-x-12">
            <div className="flex items-center py-12 space-x-12">
              <Link className="text-white" to="/" >Home</Link>
<<<<<<< HEAD
              <Link className="text-white" to="/produtos/add" >Adiciona produtos</Link>
=======
              <Link className="text-white" to="/produtos" >Produtos</Link>
              <Link className="text-white" to="/produtos/add" >Adiciona produtos</Link>
            </div>
            <div className="space-x-5">
              <Link className="text-white p-5 bg-blue-high rounded-lg transition-all hover:bg-blue-light" to="/login" >Login</Link>
              <Link className="text-white p-5 bg-blue-high rounded-lg transition-all hover:bg-blue-light" to="/cadastro" >Cadastro</Link>
>>>>>>> b3750a862935f51c433f14322ee44e3c12ff878c
            </div>
          </div>
        </div>
      </header>
    </div>
  );
}
